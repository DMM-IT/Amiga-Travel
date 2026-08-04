<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\FerryRoute;
use App\Models\Schedule;
use App\Models\ServiceCancellation;
use App\Models\ServiceCancellationReplacementSchedule;
use App\Models\User;
use App\Services\ServiceCancellationManager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ServiceCancellationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected FerryRoute $route;
    protected Schedule $schedule;
    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->admin = User::factory()->create([
            'is_admin' => true,
            'is_staff' => true,
        ]);

        $this->customer = User::factory()->create([
            'is_admin' => false,
            'is_staff' => false,
        ]);

        $this->route = FerryRoute::create([
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'mode' => 'ferry',
            'operator' => 'Starlite',
            'is_active' => true,
        ]);

        $this->schedule = Schedule::create([
            'ferry_route_id' => $this->route->id,
            'service_name' => 'Starlite Eagle',
            'departure_time' => '2026-08-01 08:00:00',
            'arrival_time' => '2026-08-01 10:00:00',
            'duration_minutes' => 120,
            'price' => 500.00,
            'is_active' => true,
        ]);

        $this->booking = Booking::create([
            'user_id' => $this->customer->id,
            'transaction_number' => 'AGT-TEST-0001',
            'origin' => 'Calapan',
            'destination' => 'Batangas',
            'departure_date' => '2026-08-01',
            'schedule_id' => $this->schedule->id,
            'schedule_service' => 'Starlite Eagle',
            'schedule_departure_time' => '08:00',
            'schedule_arrival_time' => '10:00',
            'schedule_price' => 500.00,
            'status' => 'confirmed',
            'total_price' => 500.00,
            'client_email' => 'customer@example.com',
            'client_name' => 'Juan Dela Cruz',
        ]);
    }

    public function test_admin_can_finalize_cancellation_by_specific_schedule(): void
    {
        $manager = app(ServiceCancellationManager::class);

        $cancellation = $manager->finalizeCancellation([
            'service_type' => 'ferry',
            'carrier' => 'Starlite',
            'scope' => 'specific_schedule',
            'schedule_id' => $this->schedule->id,
            'affected_date' => '2026-08-01',
            'reason_category' => 'weather',
            'customer_message' => 'Cancelled due to severe typhoon advisory.',
            'resume_date' => '2026-08-03',
        ], $this->admin);

        $this->assertDatabaseHas('service_cancellations', [
            'id' => $cancellation->id,
            'carrier' => 'Starlite',
            'reason_category' => 'weather',
        ]);

        $this->booking->refresh();
        $this->assertEquals('operator_cancelled', $this->booking->status);
        $this->assertEquals('cancelled_by_operator_rescheduling_required', $this->booking->disruption_status);
        $this->assertEquals($cancellation->id, $this->booking->service_cancellation_id);
    }

    public function test_customer_cannot_select_replacement_date_before_resume_date(): void
    {
        $manager = app(ServiceCancellationManager::class);

        $cancellation = $manager->finalizeCancellation([
            'service_type' => 'ferry',
            'carrier' => 'Starlite',
            'scope' => 'specific_schedule',
            'schedule_id' => $this->schedule->id,
            'affected_date' => '2026-08-01',
            'reason_category' => 'weather',
            'customer_message' => 'Cancelled due to typhoon.',
            'resume_date' => '2026-08-05',
        ], $this->admin);

        $this->booking->refresh();

        $this->expectException(\InvalidArgumentException::class);

        // Attempting to select a date before resume date (2026-08-03 < 2026-08-05)
        $manager->submitCustomerReschedule($this->booking, $this->schedule->id, '2026-08-03');
    }

    public function test_customer_can_submit_eligible_reschedule_request(): void
    {
        $manager = app(ServiceCancellationManager::class);

        $cancellation = $manager->finalizeCancellation([
            'service_type' => 'ferry',
            'carrier' => 'Starlite',
            'scope' => 'specific_schedule',
            'schedule_id' => $this->schedule->id,
            'affected_date' => '2026-08-01',
            'reason_category' => 'weather',
            'customer_message' => 'Cancelled due to typhoon.',
            'resume_date' => '2026-08-03',
        ], $this->admin);

        $replacementSchedule = Schedule::create([
            'ferry_route_id' => $this->route->id,
            'service_name' => 'Starlite Eagle',
            'departure_time' => '2026-08-03 08:00:00',
            'arrival_time' => '2026-08-03 10:00:00',
            'duration_minutes' => 120,
            'price' => 500.00,
            'is_active' => true,
        ]);

        ServiceCancellationReplacementSchedule::create([
            'service_cancellation_id' => $cancellation->id,
            'schedule_id' => $replacementSchedule->id,
            'replacement_date' => '2026-08-03',
        ]);

        $this->booking->refresh();

        $success = $manager->submitCustomerReschedule($this->booking, $replacementSchedule->id, '2026-08-03');

        $this->assertTrue($success);
        $this->booking->refresh();
        $this->assertEquals('reschedule_requested', $this->booking->disruption_status);
        $this->assertEquals($replacementSchedule->id, $this->booking->preferred_replacement_schedule_id);
    }

    public function test_staff_can_approve_reschedule_request_with_zero_fees(): void
    {
        $manager = app(ServiceCancellationManager::class);

        $cancellation = $manager->finalizeCancellation([
            'service_type' => 'ferry',
            'carrier' => 'Starlite',
            'scope' => 'specific_schedule',
            'schedule_id' => $this->schedule->id,
            'affected_date' => '2026-08-01',
            'reason_category' => 'weather',
            'customer_message' => 'Cancelled due to typhoon.',
            'resume_date' => '2026-08-03',
        ], $this->admin);

        $replacementSchedule = Schedule::create([
            'ferry_route_id' => $this->route->id,
            'service_name' => 'Starlite Eagle',
            'departure_time' => '2026-08-03 08:00:00',
            'arrival_time' => '2026-08-03 10:00:00',
            'duration_minutes' => 120,
            'price' => 700.00, // Higher price, but fare difference must be 0
            'is_active' => true,
        ]);

        ServiceCancellationReplacementSchedule::create([
            'service_cancellation_id' => $cancellation->id,
            'schedule_id' => $replacementSchedule->id,
            'replacement_date' => '2026-08-03',
        ]);

        $this->booking->refresh();
        $manager->submitCustomerReschedule($this->booking, $replacementSchedule->id, '2026-08-03');

        $this->booking->refresh();
        $manager->processStaffApproval($this->booking, true, 'Approved by staff.', $this->admin);

        $this->booking->refresh();
        $this->assertEquals('confirmed', $this->booking->status);
        $this->assertEquals('rescheduled_approved', $this->booking->disruption_status);
        $this->assertEquals('2026-08-03', $this->booking->departure_date->format('Y-m-d'));
        // Original price must remain 500 (no extra fare charge)
        $this->assertEquals(500.00, $this->booking->total_price);
    }
}
