<?php

namespace Database\Seeders;

use App\Models\FerryRoute;
use App\Models\Schedule;
use App\Models\ScheduleAccommodation;
use App\Models\TransportClass;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $routesData = [
                // 1. Calapan <-> Batangas (Starlite Ferries Inc.)
                [
                    'origin' => 'Calapan',
                    'destination' => 'Batangas',
                    'mode' => 'ferry',
                    'operator' => 'Starlite Ferries Inc.',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'Starlite Eagle', 'vehicle_name' => 'MV Starlite Eagle', 'plate_no' => 'STE-101', 'dep_time' => '08:00:00', 'duration' => 120, 'price' => 450.00],
                        ['service_name' => 'Starlite Pioneer', 'vehicle_name' => 'MV Starlite Pioneer', 'plate_no' => 'STP-102', 'dep_time' => '14:00:00', 'duration' => 120, 'price' => 450.00],
                        ['service_name' => 'Starlite Saturn', 'vehicle_name' => 'MV Starlite Saturn', 'plate_no' => 'STS-103', 'dep_time' => '20:00:00', 'duration' => 120, 'price' => 450.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Reclining Seats', 'description' => 'Comfortable air-conditioned reclining seats.', 'price' => 450.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Economy Bed', 'description' => 'Air-conditioned bunk bed accommodation.', 'price' => 650.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Tourist Bed', 'description' => 'Spacious tourist class bed accommodation.', 'price' => 850.00, 'has_bed' => true, 'sort_order' => 3],
                        ['name' => 'VIP Cabin', 'description' => 'Private cabin with exclusive amenities and restroom.', 'price' => 1800.00, 'has_bed' => true, 'sort_order' => 4],
                    ],
                ],
                [
                    'origin' => 'Batangas',
                    'destination' => 'Calapan',
                    'mode' => 'ferry',
                    'operator' => 'Starlite Ferries Inc.',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'Starlite Eagle', 'vehicle_name' => 'MV Starlite Eagle', 'plate_no' => 'STE-101', 'dep_time' => '08:00:00', 'duration' => 120, 'price' => 450.00],
                        ['service_name' => 'Starlite Pioneer', 'vehicle_name' => 'MV Starlite Pioneer', 'plate_no' => 'STP-102', 'dep_time' => '14:00:00', 'duration' => 120, 'price' => 450.00],
                        ['service_name' => 'Starlite Saturn', 'vehicle_name' => 'MV Starlite Saturn', 'plate_no' => 'STS-103', 'dep_time' => '20:00:00', 'duration' => 120, 'price' => 450.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Reclining Seats', 'description' => 'Comfortable air-conditioned reclining seats.', 'price' => 450.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Economy Bed', 'description' => 'Air-conditioned bunk bed accommodation.', 'price' => 650.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Tourist Bed', 'description' => 'Spacious tourist class bed accommodation.', 'price' => 850.00, 'has_bed' => true, 'sort_order' => 3],
                        ['name' => 'VIP Cabin', 'description' => 'Private cabin with exclusive amenities and restroom.', 'price' => 1800.00, 'has_bed' => true, 'sort_order' => 4],
                    ],
                ],

                // 2. Batangas <-> Caticlan (2GO Travel)
                [
                    'origin' => 'Batangas',
                    'destination' => 'Caticlan',
                    'mode' => 'ferry',
                    'operator' => '2GO Travel',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'MV 2GO Maligaya', 'vehicle_name' => 'MV 2GO Maligaya', 'plate_no' => '2GO-201', 'dep_time' => '09:00:00', 'duration' => 540, 'price' => 1200.00],
                        ['service_name' => 'MV 2GO Masagana', 'vehicle_name' => 'MV 2GO Masagana', 'plate_no' => '2GO-202', 'dep_time' => '21:00:00', 'duration' => 540, 'price' => 1200.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Super Value Class', 'description' => 'Budget-friendly open-air bunk beds.', 'price' => 1200.00, 'has_bed' => true, 'sort_order' => 1],
                        ['name' => 'Tourist Class', 'description' => 'Air-conditioned shared cabin bunk beds.', 'price' => 1500.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Cabin Class', 'description' => 'Shared 4-berth or 6-berth cabin with privacy.', 'price' => 2200.00, 'has_bed' => true, 'sort_order' => 3],
                        ['name' => 'State Room', 'description' => 'Private luxury suite with private bathroom and TV.', 'price' => 3500.00, 'has_bed' => true, 'sort_order' => 4],
                    ],
                ],
                [
                    'origin' => 'Caticlan',
                    'destination' => 'Batangas',
                    'mode' => 'ferry',
                    'operator' => '2GO Travel',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'MV 2GO Maligaya', 'vehicle_name' => 'MV 2GO Maligaya', 'plate_no' => '2GO-201', 'dep_time' => '09:00:00', 'duration' => 540, 'price' => 1200.00],
                        ['service_name' => 'MV 2GO Masagana', 'vehicle_name' => 'MV 2GO Masagana', 'plate_no' => '2GO-202', 'dep_time' => '21:00:00', 'duration' => 540, 'price' => 1200.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Super Value Class', 'description' => 'Budget-friendly open-air bunk beds.', 'price' => 1200.00, 'has_bed' => true, 'sort_order' => 1],
                        ['name' => 'Tourist Class', 'description' => 'Air-conditioned shared cabin bunk beds.', 'price' => 1500.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Cabin Class', 'description' => 'Shared 4-berth or 6-berth cabin with privacy.', 'price' => 2200.00, 'has_bed' => true, 'sort_order' => 3],
                        ['name' => 'State Room', 'description' => 'Private luxury suite with private bathroom and TV.', 'price' => 3500.00, 'has_bed' => true, 'sort_order' => 4],
                    ],
                ],

                // 3. Manila <-> Cebu (2GO Travel)
                [
                    'origin' => 'Manila',
                    'destination' => 'Cebu',
                    'mode' => 'ferry',
                    'operator' => '2GO Travel',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'MV St. Michael the Archangel', 'vehicle_name' => 'MV St. Michael the Archangel', 'plate_no' => 'SMA-301', 'dep_time' => '10:00:00', 'duration' => 1320, 'price' => 1800.00],
                        ['service_name' => 'MV St. Francis Xavier', 'vehicle_name' => 'MV St. Francis Xavier', 'plate_no' => 'SFX-302', 'dep_time' => '18:00:00', 'duration' => 1320, 'price' => 1800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Super Value Class', 'description' => 'Budget-friendly open-air bunk beds.', 'price' => 1800.00, 'has_bed' => true, 'sort_order' => 1],
                        ['name' => 'Tourist Class', 'description' => 'Air-conditioned shared cabin bunk beds.', 'price' => 2300.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Cabin Class', 'description' => 'Shared 4-berth cabin with comfort amenities.', 'price' => 3500.00, 'has_bed' => true, 'sort_order' => 3],
                        ['name' => 'State Room', 'description' => 'Private state room with en-suite bath and lounge.', 'price' => 5000.00, 'has_bed' => true, 'sort_order' => 4],
                    ],
                ],
                [
                    'origin' => 'Cebu',
                    'destination' => 'Manila',
                    'mode' => 'ferry',
                    'operator' => '2GO Travel',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'MV St. Michael the Archangel', 'vehicle_name' => 'MV St. Michael the Archangel', 'plate_no' => 'SMA-301', 'dep_time' => '10:00:00', 'duration' => 1320, 'price' => 1800.00],
                        ['service_name' => 'MV St. Francis Xavier', 'vehicle_name' => 'MV St. Francis Xavier', 'plate_no' => 'SFX-302', 'dep_time' => '18:00:00', 'duration' => 1320, 'price' => 1800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Super Value Class', 'description' => 'Budget-friendly open-air bunk beds.', 'price' => 1800.00, 'has_bed' => true, 'sort_order' => 1],
                        ['name' => 'Tourist Class', 'description' => 'Air-conditioned shared cabin bunk beds.', 'price' => 2300.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Cabin Class', 'description' => 'Shared 4-berth cabin with comfort amenities.', 'price' => 3500.00, 'has_bed' => true, 'sort_order' => 3],
                        ['name' => 'State Room', 'description' => 'Private state room with en-suite bath and lounge.', 'price' => 5000.00, 'has_bed' => true, 'sort_order' => 4],
                    ],
                ],

                // 4. Roxas <-> Caticlan (Starlite Ferries Inc.)
                [
                    'origin' => 'Roxas',
                    'destination' => 'Caticlan',
                    'mode' => 'ferry',
                    'operator' => 'Starlite Ferries Inc.',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'Starlite Archer', 'vehicle_name' => 'MV Starlite Archer', 'plate_no' => 'STA-401', 'dep_time' => '06:00:00', 'duration' => 240, 'price' => 800.00],
                        ['service_name' => 'Starlite Venus', 'vehicle_name' => 'MV Starlite Venus', 'plate_no' => 'STV-402', 'dep_time' => '14:00:00', 'duration' => 240, 'price' => 800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Reclining Seats', 'description' => 'Air-conditioned comfortable reclining seats.', 'price' => 800.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Economy Bed', 'description' => 'Standard bunk bed accommodation.', 'price' => 1050.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Tourist Bed', 'description' => 'Premium tourist bed with curtains and charging port.', 'price' => 1350.00, 'has_bed' => true, 'sort_order' => 3],
                    ],
                ],
                [
                    'origin' => 'Caticlan',
                    'destination' => 'Roxas',
                    'mode' => 'ferry',
                    'operator' => 'Starlite Ferries Inc.',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'Starlite Archer', 'vehicle_name' => 'MV Starlite Archer', 'plate_no' => 'STA-401', 'dep_time' => '06:00:00', 'duration' => 240, 'price' => 800.00],
                        ['service_name' => 'Starlite Venus', 'vehicle_name' => 'MV Starlite Venus', 'plate_no' => 'STV-402', 'dep_time' => '14:00:00', 'duration' => 240, 'price' => 800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Reclining Seats', 'description' => 'Air-conditioned comfortable reclining seats.', 'price' => 800.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Economy Bed', 'description' => 'Standard bunk bed accommodation.', 'price' => 1050.00, 'has_bed' => true, 'sort_order' => 2],
                        ['name' => 'Tourist Bed', 'description' => 'Premium tourist bed with curtains and charging port.', 'price' => 1350.00, 'has_bed' => true, 'sort_order' => 3],
                    ],
                ],

                // 5. Manila <-> Cebu (Cebu Pacific)
                [
                    'origin' => 'Manila',
                    'destination' => 'Cebu',
                    'mode' => 'airline',
                    'operator' => 'Cebu Pacific',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => '5J 562', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-C3201', 'dep_time' => '07:30:00', 'duration' => 80, 'price' => 2500.00],
                        ['service_name' => '5J 564', 'vehicle_name' => 'Airbus A321', 'plate_no' => 'RP-C3202', 'dep_time' => '15:00:00', 'duration' => 80, 'price' => 2800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Standard', 'description' => 'Regular economy seat across main cabin.', 'price' => 2500.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Standard Plus', 'description' => 'Extra legroom seat near cabin front.', 'price' => 3300.00, 'has_bed' => false, 'sort_order' => 2],
                        ['name' => 'Premium', 'description' => 'Priority boarding and exit row extra legroom.', 'price' => 4000.00, 'has_bed' => false, 'sort_order' => 3],
                    ],
                ],
                [
                    'origin' => 'Cebu',
                    'destination' => 'Manila',
                    'mode' => 'airline',
                    'operator' => 'Cebu Pacific',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => '5J 563', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-C3201', 'dep_time' => '09:30:00', 'duration' => 80, 'price' => 2500.00],
                        ['service_name' => '5J 565', 'vehicle_name' => 'Airbus A321', 'plate_no' => 'RP-C3202', 'dep_time' => '17:00:00', 'duration' => 80, 'price' => 2800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Standard', 'description' => 'Regular economy seat across main cabin.', 'price' => 2500.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Standard Plus', 'description' => 'Extra legroom seat near cabin front.', 'price' => 3300.00, 'has_bed' => false, 'sort_order' => 2],
                        ['name' => 'Premium', 'description' => 'Priority boarding and exit row extra legroom.', 'price' => 4000.00, 'has_bed' => false, 'sort_order' => 3],
                    ],
                ],

                // 6. Manila <-> Davao (Philippine Airlines)
                [
                    'origin' => 'Manila',
                    'destination' => 'Davao',
                    'mode' => 'airline',
                    'operator' => 'Philippine Airlines',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'PR 1813', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-P3201', 'dep_time' => '06:00:00', 'duration' => 110, 'price' => 3500.00],
                        ['service_name' => 'PR 1815', 'vehicle_name' => 'Airbus A321', 'plate_no' => 'RP-P3202', 'dep_time' => '17:00:00', 'duration' => 110, 'price' => 3800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Economy Class', 'description' => 'Standard seating with complimentary snacks.', 'price' => 3500.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Premium Economy', 'description' => 'Extra legroom and priority baggage handling.', 'price' => 5000.00, 'has_bed' => false, 'sort_order' => 2],
                        ['name' => 'Business Class', 'description' => 'Luxury seating with gourmet dining and lounge access.', 'price' => 9000.00, 'has_bed' => false, 'sort_order' => 3],
                    ],
                ],
                [
                    'origin' => 'Davao',
                    'destination' => 'Manila',
                    'mode' => 'airline',
                    'operator' => 'Philippine Airlines',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'PR 1814', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-P3201', 'dep_time' => '08:30:00', 'duration' => 110, 'price' => 3500.00],
                        ['service_name' => 'PR 1816', 'vehicle_name' => 'Airbus A321', 'plate_no' => 'RP-P3202', 'dep_time' => '19:30:00', 'duration' => 110, 'price' => 3800.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Economy Class', 'description' => 'Standard seating with complimentary snacks.', 'price' => 3500.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Premium Economy', 'description' => 'Extra legroom and priority baggage handling.', 'price' => 5000.00, 'has_bed' => false, 'sort_order' => 2],
                        ['name' => 'Business Class', 'description' => 'Luxury seating with gourmet dining and lounge access.', 'price' => 9000.00, 'has_bed' => false, 'sort_order' => 3],
                    ],
                ],

                // 7. Manila <-> Boracay (Caticlan) (Philippine AirAsia)
                [
                    'origin' => 'Manila',
                    'destination' => 'Boracay (Caticlan)',
                    'mode' => 'airline',
                    'operator' => 'Philippine AirAsia',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'Z2 221', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-A3201', 'dep_time' => '09:15:00', 'duration' => 65, 'price' => 2200.00],
                        ['service_name' => 'Z2 223', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-A3202', 'dep_time' => '16:45:00', 'duration' => 65, 'price' => 2400.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Economy Class', 'description' => 'Budget-friendly standard seating.', 'price' => 2200.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Hot Seats', 'description' => 'Forward and exit-row seating with priority boarding.', 'price' => 3200.00, 'has_bed' => false, 'sort_order' => 2],
                    ],
                ],
                [
                    'origin' => 'Boracay (Caticlan)',
                    'destination' => 'Manila',
                    'mode' => 'airline',
                    'operator' => 'Philippine AirAsia',
                    'trip_type' => 'local',
                    'schedules' => [
                        ['service_name' => 'Z2 222', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-A3201', 'dep_time' => '11:00:00', 'duration' => 65, 'price' => 2200.00],
                        ['service_name' => 'Z2 224', 'vehicle_name' => 'Airbus A320', 'plate_no' => 'RP-A3202', 'dep_time' => '18:30:00', 'duration' => 65, 'price' => 2400.00],
                    ],
                    'accommodations' => [
                        ['name' => 'Economy Class', 'description' => 'Budget-friendly standard seating.', 'price' => 2200.00, 'has_bed' => false, 'sort_order' => 1],
                        ['name' => 'Hot Seats', 'description' => 'Forward and exit-row seating with priority boarding.', 'price' => 3200.00, 'has_bed' => false, 'sort_order' => 2],
                    ],
                ],
            ];

            // Generate schedules for dates from today until 30 days ahead
            $startDate = Carbon::today();
            $endDate = Carbon::today()->addDays(30);

            foreach ($routesData as $rData) {
                // Find matching vehicle if any
                $vehicle = Vehicle::query()
                    ->where('name', 'like', '%' . ($rData['schedules'][0]['vehicle_name'] ?? '') . '%')
                    ->orWhere('operator', 'like', '%' . $rData['operator'] . '%')
                    ->first();

                // If no vehicle found, create a basic Vehicle record from first schedule info
                if (! $vehicle) {
                    $firstSched = $rData['schedules'][0] ?? null;
                    $plateNo = $firstSched['plate_no'] ?? null;
                    $vehicleName = $firstSched['vehicle_name'] ?? ($rData['operator'] ?? '');

                    $vehicle = Vehicle::firstOrCreate(
                        ['vehicle_id' => $plateNo, 'name' => $vehicleName],
                        [
                            'type' => ($rData['mode'] === 'airline' ? 'airline' : 'ferry'),
                            'operator' => $rData['operator'] ?? null,
                            'description' => $rData['operator'] ?? null,
                            'capacity' => null,
                            'is_active' => true,
                        ]
                    );
                }

                // 1. Create or update FerryRoute
                // Match existing routes by origin/destination/operator only so we can
                // correct the `mode` (airline/ferry) if it differs from the seed data.
                $route = FerryRoute::updateOrCreate(
                    [
                        'origin' => $rData['origin'],
                        'destination' => $rData['destination'],
                        'operator' => $rData['operator'],
                    ],
                    [
                        'mode' => $rData['mode'],
                        'trip_type' => $rData['trip_type'],
                        'is_active' => true,
                        'vehicle_id' => $vehicle?->id,
                    ]
                );

                // 2. Prepare TransportClass records for this operator & accommodations
                $transportClasses = [];
                foreach ($rData['accommodations'] as $accData) {
                    $code = str($accData['name'])->slug()->value();
                    $tc = TransportClass::updateOrCreate(
                        [
                            'operator' => $rData['operator'],
                            'code' => $code,
                        ],
                        [
                            'name' => $accData['name'],
                            'description' => $accData['description'] ?? null,
                            'price' => $accData['price'] ?? 0,
                            'is_active' => true,
                            'sort_order' => $accData['sort_order'] ?? 1,
                        ]
                    );
                    $transportClasses[$accData['name']] = $tc;
                }

                // 3. Create daily schedules across the date range
                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    foreach ($rData['schedules'] as $sData) {
                        $departureTime = Carbon::parse($date->format('Y-m-d') . ' ' . $sData['dep_time']);
                        $arrivalTime = $departureTime->copy()->addMinutes($sData['duration']);

                        $schedule = Schedule::updateOrCreate(
                            [
                                'ferry_route_id' => $route->id,
                                'service_name' => $sData['service_name'],
                                'departure_time' => $departureTime,
                            ],
                            [
                                'vehicle_name' => $sData['vehicle_name'],
                                'plate_no' => $sData['plate_no'],
                                'arrival_time' => $arrivalTime,
                                'duration_minutes' => $sData['duration'],
                                'price' => $sData['price'],
                                'availability_label' => 'Available',
                                'seat_rows' => 15,
                                'seat_columns' => ['A', 'B', 'C', 'D', 'E', 'F'],
                                'is_active' => true,
                            ]
                        );

                        // Attach ScheduleAccommodations & TransportClasses
                        $pivotData = [];
                        foreach ($rData['accommodations'] as $accData) {
                            // ScheduleAccommodation (hasMany relation)
                            ScheduleAccommodation::updateOrCreate(
                                [
                                    'schedule_id' => $schedule->id,
                                    'name' => $accData['name'],
                                ],
                                [
                                    'description' => $accData['description'],
                                    'price' => $accData['price'],
                                    'tickets_available' => 50,
                                    'has_bed' => $accData['has_bed'],
                                    'is_active' => true,
                                    'sort_order' => $accData['sort_order'],
                                ]
                            );

                            // Collect pivot data for transportClasses relation
                            if (isset($transportClasses[$accData['name']])) {
                                $tc = $transportClasses[$accData['name']];
                                $pivotData[$tc->id] = [
                                    'additional_price' => $accData['price'],
                                    'tickets_available' => 50,
                                    'description' => $accData['description'],
                                    'has_bed' => $accData['has_bed'],
                                    'is_active' => true,
                                ];
                            }
                        }

                        if (!empty($pivotData)) {
                            $schedule->transportClasses()->syncWithoutDetaching($pivotData);
                        }
                    }
                }
            }
        });
    }
}
