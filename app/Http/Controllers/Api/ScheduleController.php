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
        $origins = FerryRoute::activeOrigins($mode ?: null, $operator ?: null);
        return response()->json([
            'status' => 'success',
            'origins' => $origins
        ]);
    }

    public function operators(Request $request)
    {
        $mode = $request->input('mode', '');
        $operators = FerryRoute::activeOperatorsFor($mode ?: null);
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
        $mode = $request->input('mode', '');
        $operator = $request->input('operator', '');
        $destinations = FerryRoute::activeDestinationsFor($request->input('origin'), $mode ?: null, $operator ?: null);
        return response()->json([
            'status' => 'success',
            'destinations' => $destinations
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $date = $request->input('date');
        $mode = $request->input('mode', null);
        $operator = $request->input('operator', null);

        $activeRule = \App\Models\GraciaEarningRule::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest('id')
            ->first();

        $schedules = Schedule::forRouteAndDate($origin, $destination, $date, $mode, $operator)
            ->get()
            ->map(function ($schedule) use ($date, $activeRule) {
                $arr = $schedule->toBookingArray($date);
                $pts = 0;
                if ($activeRule && $activeRule->spend_threshold_centavos > 0) {
                    $pts = (int) floor(($arr['price'] * 100) / $activeRule->spend_threshold_centavos) * $activeRule->points_awarded;
                }
                $arr['gracia_points'] = $pts;
                return $arr;
            });

        return response()->json([
            'status' => 'success',
            'schedules' => $schedules
        ]);
    }
    public function allSchedules(Request $request)
    {
        $startDate = $request->query('start_date', \Carbon\Carbon::today()->format('Y-m-d'));
        $endDate = $request->query('end_date', \Carbon\Carbon::today()->addDays(6)->format('Y-m-d'));

        $routes = FerryRoute::with([
            'schedules' => function ($query) use ($startDate, $endDate) {
                $query->where('is_active', true)
                      ->whereBetween('departure_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                      ->orderBy('departure_time');
            },
            'schedules.scheduleAccommodations',
            'schedules.transportClasses',
        ])->where('is_active', true)->orderBy('origin')->orderBy('destination')->get();
        
        // Filter out routes that have no schedules in this date range
        $routes = $routes->filter(fn ($route) => $route->schedules->isNotEmpty())->values();

        return response()->json([
            'status' => 'success',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'routes' => $routes
        ]);
    }
}
