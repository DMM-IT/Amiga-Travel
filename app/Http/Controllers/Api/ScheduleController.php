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
        $origins = FerryRoute::activeOrigins($mode ?: null);
        return response()->json([
            'status' => 'success',
            'origins' => $origins
        ]);
    }

    public function destinations(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
        ]);
        $mode = $request->input('mode', '');
        $destinations = FerryRoute::activeDestinationsFor($request->input('origin'), $mode ?: null);
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

        $schedules = Schedule::forRouteAndDate($origin, $destination, $date, $mode)
            ->get()
            ->map(fn($schedule) => $schedule->toBookingArray($date));

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
