<?php

namespace Tests\Feature;

use App\Models\Accommodation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AccommodationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_accommodations_by_destination_when_requested(): void
    {
        Cache::flush();

        Accommodation::query()->delete();

        Accommodation::create([
            'name' => 'Ocean View Hotel',
            'destination' => 'Puerto Princesa',
            'description' => 'Beachfront hotel',
            'price' => 1500,
            'images' => [],
            'is_active' => true,
        ]);

        Accommodation::create([
            'name' => 'Mountain Stay',
            'destination' => 'Bohol',
            'description' => 'Mountain lodge',
            'price' => 1800,
            'images' => [],
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/accommodations?destination=Puerto%20Princesa');

        $response->assertOk()
            ->assertJsonCount(1, 'accommodations')
            ->assertJsonPath('accommodations.0.name', 'Ocean View Hotel');
    }
}
