    public function rebookCalculation(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'dep_schedule_id' => 'required|exists:schedules,id',
            'dep_accommodation_id' => 'nullable|exists:transport_classes,id',
            'ret_schedule_id' => 'nullable|exists:schedules,id',
            'ret_accommodation_id' => 'nullable|exists:transport_classes,id',
            'is_round_trip' => 'required|boolean'
        ]);

        $booking = Booking::whereKey($id)
            ->where('client_email', $request->input('email'))
            ->firstOrFail();

        $passengerCount = $booking->passengers()->count() ?: 1;
        $mode = $booking->getMode();
        $isAirline = $mode === 'airline';

        $booking->loadMissing('transportClasses');
        $tcs = $booking->transportClasses->values();
        $depTCPerPax = (float) optional($tcs->get(0))->pivot?->price;
        $retTCPerPax = (float) optional($tcs->get(1))->pivot?->price;

        $origDepPerPax = (float)($booking->schedule_price ?? 0)
                       + $depTCPerPax
                       + (float)($booking->schedule_accommodation_price ?? 0);
        $origRetPerPax = (float)($booking->return_schedule_price ?? 0)
                       + $retTCPerPax
                       + (float)($booking->return_schedule_accommodation_price ?? 0);

        $originalFare = ($origDepPerPax + $origRetPerPax) * $passengerCount;

        $newTotal = 0.0;
        
        $depSchedule = \App\Models\Schedule::find($request->input('dep_schedule_id'));
        $depAccPrice = 0;
        if ($request->input('dep_accommodation_id')) {
            $tc = $depSchedule->transportClasses()->where('transport_classes.id', $request->input('dep_accommodation_id'))->first();
            $depAccPrice = $tc ? $tc->pivot->price : 0;
        }

        $retSchedule = $request->input('ret_schedule_id') ? \App\Models\Schedule::find($request->input('ret_schedule_id')) : null;
        $retAccPrice = 0;
        if ($request->input('ret_accommodation_id') && $retSchedule) {
            $tc = $retSchedule->transportClasses()->where('transport_classes.id', $request->input('ret_accommodation_id'))->first();
            $retAccPrice = $tc ? $tc->pivot->price : 0;
        }

        if ($isAirline) {
            $depPerPax = $depAccPrice / 1.5;
            $newTotal += $depPerPax * $passengerCount;
            if ($request->input('is_round_trip')) {
                $retPerPax = $retAccPrice / 1.5;
                $newTotal += $retPerPax * $passengerCount;
            }
        } else {
            $depPerPax = ($depSchedule->price ?? 0) + $depAccPrice;
            $newTotal += $depPerPax * $passengerCount;
            if ($request->input('is_round_trip')) {
                $retPerPax = ($retSchedule->price ?? 0) + $retAccPrice;
                $newTotal += $retPerPax * $passengerCount;
            }
        }

        if ($booking->has_vehicle) {
            $newTotal += $booking->vehicle_price;
        }

        $settings = \App\Models\PaymentSetting::current();
        $isAfterDeparture = $booking->isAfterDeparture();

        $revalidation_fee = floatval($settings->revalidation_fee) * $passengerCount;

        $surchargePct = 0;
        if ($isAirline) {
            $surchargePct = (float)$settings->rebook_airline_before_departure_surcharge_pct;
        } elseif ($isAfterDeparture) {
            $surchargePct = (float)$settings->rebook_ferry_after_departure_surcharge_pct;
        } else {
            $surchargePct = (float)$settings->rebook_ferry_before_departure_surcharge_pct;
        }
        
        $surcharge = $originalFare * ($surchargePct / 100);
        $rate_diff = max(0, $newTotal - $originalFare);
        $total_to_pay = $surcharge + $revalidation_fee + $rate_diff;

        return response()->json([
            'status' => 'success',
            'breakdown' => [
                'rate_diff' => (float) $rate_diff,
                'surcharge' => (float) $surcharge,
                'revalidation_fee' => (float) $revalidation_fee,
                'total_to_pay' => (float) $total_to_pay,
            ],
            'qr_code_url' => $settings->qr_code_path ? asset('storage/' . $settings->qr_code_path) : null,
        ]);
    }
