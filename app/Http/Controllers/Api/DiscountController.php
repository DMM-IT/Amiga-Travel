<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('name')->get()->map(function ($d) {
            return [
                'id' => $d->id,
                'name' => $d->name,
                'percentage' => floatval($d->percentage),
            ];
        });

        return response()->json([
            'status' => 'success',
            'discounts' => $discounts,
        ]);
    }
}
