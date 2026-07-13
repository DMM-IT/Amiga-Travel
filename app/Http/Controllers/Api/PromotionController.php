<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('is_active', true)->get()->map(function ($promo) {
            return [
                'id' => $promo->id,
                'image_url' => $promo->image_path
                    ? url('storage/' . $promo->image_path)
                    : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'promotions' => $promotions,
        ]);
    }
}
