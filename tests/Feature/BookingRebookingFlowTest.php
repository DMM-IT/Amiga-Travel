<?php

namespace Tests\Feature;

use App\Livewire\BookingLookup;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class BookingRebookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_rebooking_request_is_stored_as_pending_until_verified(): void
    {
        Storage::fake('public');

        $booking = Booking::create([
            'transaction_number' => 'AGT-REBOOK-001',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'paid',
        ]);

        $newDeparture = now()->addDays(7)->toDateString();
        $newReturn = now()->addDays(10)->toDateString();

        Livewire::test(BookingLookup::class)
            ->set('transaction_number', $booking->transaction_number)
            ->call('search')
            ->call('requestRebooking')
            ->set('rebooking_departure_date', $newDeparture)
            ->set('rebooking_return_date', $newReturn)
            ->set('rebookingProof', UploadedFile::fake()->image('proof.jpg'))
            ->call('submitRebookingProof');

        $booking->refresh();

        $this->assertSame('pending', $booking->rebooking_status);
        $this->assertSame($newDeparture, $booking->rebooking_departure_date->toDateString());
        $this->assertSame($newReturn, $booking->rebooking_return_date->toDateString());
        $this->assertSame(360.0, (float) $booking->transaction->rebooking_fee);
        $this->assertSame($booking->departure_date->toDateString(), now()->addDay()->toDateString());
    }

    public function test_booking_rebooking_can_be_verified_and_dates_applied(): void
    {
        $booking = Booking::create([
            'transaction_number' => 'AGT-REBOOK-002',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
            'rebooking_status' => 'pending',
            'rebooking_departure_date' => now()->addDays(7)->toDateString(),
            'rebooking_return_date' => now()->addDays(10)->toDateString(),
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'pending',
            'rebooking_fee' => 360,
        ]);

        $booking->verifyRebooking();
        $booking->refresh();

        $this->assertSame('verified', $booking->rebooking_status);
        $this->assertSame(now()->addDays(7)->toDateString(), $booking->departure_date->toDateString());
        $this->assertSame(now()->addDays(10)->toDateString(), $booking->return_date->toDateString());
        $this->assertSame('paid', $booking->transaction->payment_status);
    }
}
