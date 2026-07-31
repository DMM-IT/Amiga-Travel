<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentDiscountProofPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_discount_proofs_are_persisted_and_stored(): void
    {
        Storage::fake('public');

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

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'payment_status' => 'pending',
        ]);

        $front = UploadedFile::fake()->image('student-front.jpg', 600, 600);
        $back = UploadedFile::fake()->image('student-back.jpg', 600, 600);

        $transaction->storeStudentDiscountProofs(
            [0 => $front],
            [0 => $back],
            [[ 'name' => 'Student One' ]],
        );

        $saved = $transaction->fresh()->student_discount_proofs;

        $this->assertIsArray($saved);
        $this->assertCount(1, $saved);
        $this->assertSame('Student One', $saved[0]['passenger_name']);
        $this->assertNotNull($saved[0]['front']);
        $this->assertNotNull($saved[0]['back']);
        Storage::disk('public')->assertExists($saved[0]['front']);
        Storage::disk('public')->assertExists($saved[0]['back']);
    }
}
