<?php

namespace Tests\Feature;

use App\Livewire\BookingLookup;
use App\Mail\BookingCancellation;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class BookingLookupCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_clicking_cancel_booking_shows_the_refund_form_immediately(): void
    {
        $booking = Booking::create([
            'transaction_number' => 'AGT-TEST-002',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'pending',
        ]);

        Livewire::test(BookingLookup::class)
            ->set('transaction_number', $booking->transaction_number)
            ->call('search')
            ->call('requestCancellation')
            ->assertSet('cancellationRequested', true)
            ->assertSet('showCancellationWarning', false);
    }

    public function test_expired_cancellation_window_hides_the_confirmation_modal_until_next_booking(): void
    {
        $booking = Booking::create([
            'transaction_number' => 'AGT-TEST-003',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'pending',
        ]);

        session(['cancellation_window_expires_for_' . $booking->transaction_number => now()->subMinute()->timestamp]);
        session(['cancellation_expired_for_' . $booking->transaction_number => true]);

        Livewire::test(BookingLookup::class)
            ->set('transaction_number', $booking->transaction_number)
            ->call('search')
            ->assertSet('cancellationExpired', true)
            ->assertSet('cancellationRequested', false)
            ->assertSet('showCancellationWarning', false)
            ->assertSet('showCancellationReminder', false);
    }

    public function test_cancellation_request_can_be_confirmed_and_send_refund_email(): void
    {
        Mail::fake();

        $booking = Booking::create([
            'transaction_number' => 'AGT-TEST-001',
            'origin' => 'Cebu',
            'destination' => 'Bohol',
            'departure_date' => now()->toDateString(),
            'status' => 'pending',
            'total_price' => 1200,
            'client_email' => 'customer@example.com',
            'client_name' => 'Jane Doe',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'pending',
        ]);

        session(['cancellation_window_expires_for_' . $booking->transaction_number => now()->addMinutes(5)->timestamp]);

        Livewire::test(BookingLookup::class)
            ->set('transaction_number', $booking->transaction_number)
            ->call('search')
            ->assertSet('cancellationWindowActive', true)
            ->assertSet('cancellationRequested', true)
            ->set('refund_destination', 'GCash 09171234567')
            ->call('confirmCancellation');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('transactions', [
            'booking_id' => $booking->id,
            'payment_status' => 'cancelled',
        ]);

        Mail::assertSent(BookingCancellation::class, function (BookingCancellation $mail) use ($booking): bool {
            return $mail->hasTo($booking->client_email);
        });
    }
}
