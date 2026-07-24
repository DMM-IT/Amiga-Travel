<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VoucherService;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = \App\Models\Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'vouchers' => $vouchers,
        ]);
    }
    public function validateVoucher(Request $request, VoucherService $voucherService)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'trip_type' => 'required|string|in:one_way,round_trip',
            'client_email' => 'required|email',
            'passengers' => 'required|array|min:1',
            'passengers.*.type' => 'required|string|in:adult,child',
            'passengers.*.discount_id' => 'nullable|integer|exists:discounts,id',
            'selected_transport_class_id' => 'nullable|integer|exists:transport_classes,id',
            'selected_schedule_accommodation_id' => 'nullable|integer|exists:schedule_accommodations,id',
            'accommodation_ids' => 'nullable|array',
            'accommodation_ids.*' => 'integer|exists:accommodations,id',
            'has_vehicle' => 'nullable|boolean',
            'vehicle_price' => 'required_if:has_vehicle,true|nullable|numeric|min:0',
        ]);
        
        $result = $voucherService->validateAndCalculate($request->voucher_code, $request->all());
        
        if (!$result['valid']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
            ], 422);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
