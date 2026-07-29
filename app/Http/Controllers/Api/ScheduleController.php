<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FerryRoute;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function origins(Request $request)
    {
        $mode = $request->input('mode', '');
        $operator = $request->input('operator', '');
        $cacheKey = "api:origins:{$mode}:{$operator}";

        $origins = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addHours(6), function () use ($mode, $operator) {
            return FerryRoute::scheduleOrigins($mode ?: null, $operator ?: null);
        });

        return response()->json([
            'status' => 'success',
            'origins' => $origins
        ]);
    }

    public function operators(Request $request)
    {
        $mode = $request->input('mode', '');
        $cacheKey = "api:operators:{$mode}";

        $operators = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addHours(6), function () use ($mode) {
            return FerryRoute::scheduleOperatorsFor($mode ?: null);
        });

        return response()->json([
            'status' => 'success',
            'operators' => $operators
        ]);
    }

    public function destinations(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
        ]);
        $origin = $request->input('origin');
        $mode = $request->input('mode', '');
        $operator = $request->input('operator', '');
        $tripType = $request->input('trip_type', 'one_way');
        $requireReturn = $tripType === 'round_trip' ? '1' : '0';
        $cacheKey = "api:destinations:{$origin}:{$mode}:{$operator}:{$requireReturn}";

        $destinations = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addHours(6), function () use ($origin, $mode, $operator, $tripType) {
            return FerryRoute::scheduleDestinationsFor($origin, $mode ?: null, $operator ?: null, $tripType === 'round_trip');
        });

        return response()->json([
            'status' => 'success',
            'destinations' => $destinations
        ]);
    }

    public function availableDates(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
        ]);

        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $mode = $request->input('mode', '');
        $operator = $request->input('operator', '');
        $cacheKey = "api:available_dates:{$origin}:{$destination}:{$mode}:{$operator}";

        $dates = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(30), function () use ($origin, $destination, $mode, $operator) {
            $query = FerryRoute::where('is_active', true)
                ->where('origin', $origin)
                ->where('destination', $destination);
                
            if ($mode) {
                $query->where('mode', $mode);
            }
            if ($operator) {
                $query->where('operator', 'like', "%{$operator}%");
            }

            $routes = $query->with(['schedules' => function($q) {
                $q->where('is_active', true)
                  ->where('departure_time', '>=', now()->startOfDay());
            }])->get();

            $datesList = [];
            foreach ($routes as $route) {
                foreach ($route->schedules as $schedule) {
                    $datesList[] = \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d');
                }
            }

            $datesList = array_values(array_unique($datesList));
            sort($datesList);
            return $datesList;
        });

        return response()->json([
            'status' => 'success',
            'available_dates' => $dates
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $origin      = $request->input('origin');
        $destination = $request->input('destination');
        $date        = $request->input('date');
        $mode        = $request->input('mode', null);
        $operator    = $request->input('operator', null);

        // Fetch the active earning rule (no need to cache a model instance to avoid unserialize errors)
        $activeRule = \App\Models\GraciaEarningRule::where('is_active', true)
            ->where(function ($q) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
            ->where(function ($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
            ->latest('id')
            ->first();

        // Cache schedule search results per route/date/mode/operator.
        // 5-minute TTL is safe: schedules don't change mid-day but we need
        // near-real-time ticket availability after busy booking windows.
        $cacheKey = 'api:schedule:search:'
            . md5("{$origin}:{$destination}:{$date}:{$mode}:{$operator}");

        $schedules = \Illuminate\Support\Facades\Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($origin, $destination, $date, $mode, $operator, $activeRule) {
                return Schedule::forRouteAndDate($origin, $destination, $date, $mode, $operator)
                    ->get()
                    ->map(function ($schedule) use ($date, $activeRule) {
                        $arr = $schedule->toBookingArray($date);
                        $pts = 0;
                        if ($activeRule && $activeRule->spend_threshold_centavos > 0) {
                            $pts = (int) floor(($arr['price'] * 100) / $activeRule->spend_threshold_centavos)
                                * $activeRule->points_awarded;
                        }
                        $arr['gracia_points'] = $pts;
                        return $arr;
                    })
                    ->values();
            }
        );

        return response()->json([
            'status'    => 'success',
            'schedules' => $schedules,
        ]);
    }
    public function allSchedules(Request $request)
    {
        $startDate = $request->query('start_date', \Carbon\Carbon::today()->format('Y-m-d'));
        $endDate   = $request->query('end_date',   \Carbon\Carbon::today()->addDays(6)->format('Y-m-d'));

        $cacheKey = 'api:all_schedules:' . $startDate . ':' . $endDate;

        $routes = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(10), function () use ($startDate, $endDate) {
            return FerryRoute::with([
                'schedules' => function ($query) use ($startDate, $endDate) {
                    $query->where('is_active', true)
                          ->whereBetween('departure_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                          ->orderBy('departure_time');
                },
                'schedules.scheduleAccommodations',
                'schedules.transportClasses',
            ])->where('is_active', true)->orderBy('origin')->orderBy('destination')->get()
              ->filter(fn ($route) => $route->schedules->isNotEmpty())
              ->values();
        });

        return response()->json([
            'status'     => 'success',
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'routes'     => $routes,
        ]);
    }
}
