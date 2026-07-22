<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiBookingCancellationRebookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_owner_can_cancel_a_booking_and_get_refund_details(): void
    {
        $booking = Booking::create([
            'transaction_number' => 'AGT-MOBILE-001',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);
        Transaction::create(['booking_id' => $booking->id, 'payment_status' => 'pending']);

        $this->postJson('/api/bookings/'.$booking->id.'/cancel', [
            'email' => 'customer@example.com',
            'action' => 'start',
        ])->assertOk();

        $response = $this->postJson('/api/bookings/'.$booking->id.'/cancel', [
            'email' => 'customer@example.com',
            'action' => 'confirm',
            'refund_destination' => 'GCash 09171234567',
        ]);

        $response->assertOk()->assertJsonPath('refund_amount', 600);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
            'refund_destination' => 'GCash 09171234567',
        ]);
        $this->assertDatabaseHas('transactions', ['booking_id' => $booking->id, 'payment_status' => 'cancelled']);
    }

    public function test_mobile_owner_can_submit_a_rebooking_request_with_proof(): void
    {
        Storage::fake('public');
        $booking = Booking::create([
            'transaction_number' => 'AGT-MOBILE-002',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);
        Transaction::create(['booking_id' => $booking->id, 'payment_status' => 'paid']);

        $response = $this->post('/api/bookings/'.$booking->id.'/rebook', [
            'email' => 'customer@example.com',
            'departure_date' => now()->addDays(7)->toDateString(),
            'return_date' => now()->addDays(10)->toDateString(),
            'proof' => UploadedFile::fake()->image('proof.jpg'),
        ]);

        $response->assertOk()->assertJsonPath('rebooking_fee', 360);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'rebooking_status' => 'pending',
            'rebooking_departure_date' => now()->addDays(7)->toDateString(),
        ]);
    }

    public function test_mobile_cannot_change_another_customer_booking(): void
    {
        $booking = Booking::create([
            'transaction_number' => 'AGT-MOBILE-003',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);

        $this->postJson('/api/bookings/'.$booking->id.'/cancel', ['email' => 'other@example.com'])
            ->assertNotFound();
    }
}