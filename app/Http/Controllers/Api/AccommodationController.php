<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($a) {
                $images = is_array($a->images) ? $a->images : [];
                return [
                    'id' => $a->id,
                    'name' => $a->name,
                    'description' => $a->description,
                    'price' => floatval($a->price),
                    'cover_image' => count($images) > 0
                        ? url('storage/' . $images[0])
                        : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'accommodations' => $accommodations,
        ]);
    }
}
