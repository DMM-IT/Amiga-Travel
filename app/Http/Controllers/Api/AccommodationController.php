<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;

class AccommodationController extends Controller
{
    public function index()
    {
        $destination = request()->query('destination');

        $accommodations = \Illuminate\Support\Facades\Cache::remember(
            'api:accommodations:' . ($destination ? strtolower(trim($destination)) : 'all'),
            now()->addHours(6),
            function () use ($destination) {
                $query = Accommodation::query()->where('is_active', true);

                if (!blank($destination)) {
                    $query->whereRaw('LOWER(destination) = ?', [strtolower(trim($destination))]);
                }

                return $query->orderBy('name')->get()->map(function ($a) {
                    $images = is_array($a->images) ? $a->images : [];
                    return [
                        'id'          => $a->id,
                        'name'        => $a->name,
                        'description' => $a->description,
                        'price'       => floatval($a->price),
                        'destination' => $a->destination,
                        'cover_image' => count($images) > 0
                            ? storage_asset_path($images[0])
                            : null,
                    ];
                });
            }
        );

        return response()->json([
            'status'         => 'success',
            'accommodations' => array_values(is_array($accommodations) ? $accommodations : $accommodations->values()->all()),
        ]);
    }
}
