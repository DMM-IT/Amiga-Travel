<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Transaction;
use App\Support\ReportingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_overall_stats_are_calculated_correctly(): void
    {
        Booking::create([
            'transaction_number' => 'AGT-TEST-001',
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'departure_date' => '2026-07-20',
            'status' => 'confirmed',
            'total_price' => 1200,
            'client_email' => 'client@example.com',
            'client_name' => 'Client One',
        ]);

        Booking::create([
            'transaction_number' => 'AGT-TEST-002',
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'departure_date' => '2026-07-21',
            'status' => 'pending',
            'total_price' => 800,
            'client_email' => 'client@example.com',
            'client_name' => 'Client Two',
        ]);

        Booking::create([
            'transaction_number' => 'AGT-TEST-003',
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'departure_date' => '2026-07-24',
            'status' => 'cancelled',
            'total_price' => 1500,
            'client_email' => 'client@example.com',
            'client_name' => 'Client Three',
        ]);

        $service = new ReportingService();
        $stats = $service->getOverallStats('all');

        $this->assertEquals(3, $stats['total_bookings']);
        $this->assertEquals(1, $stats['completed_bookings']);
        $this->assertEquals(1, $stats['pending_bookings']);
        $this->assertEquals(1, $stats['cancelled_bookings']);
    }

    public function test_booking_status_breakdown_works(): void
    {
        Booking::create([
            'transaction_number' => 'AGT-TEST-001',
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'departure_date' => '2026-07-20',
            'status' => 'confirmed',
            'total_price' => 1200,
            'client_email' => 'client@example.com',
            'client_name' => 'Client',
        ]);

        Booking::create([
            'transaction_number' => 'AGT-TEST-002',
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'departure_date' => '2026-07-21',
            'status' => 'pending',
            'total_price' => 800,
            'client_email' => 'client@example.com',
            'client_name' => 'Client',
        ]);

        $service = new ReportingService();
        $breakdown = $service->getBookingStatusBreakdown('all');

        $this->assertEquals(1, $breakdown['confirmed']);
        $this->assertEquals(1, $breakdown['pending']);
        $this->assertEquals(0, $breakdown['cancelled']);
    }
}
