<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookingFormCardClickTest extends TestCase
{
    public function test_booking_form_pre_selects_mode_and_operator_from_card_click(): void
    {
        // Simulate clicking on a card (e.g., 2GO Travel ferry card)
        $response = $this->get('/book/new?operator=2GO%20Travel&trip_type=one_way&mode=ferry');

        $response->assertStatus(200);
        $response->assertSeeLivewire('booking-form');
        // Verify the form has the pre-selected values
        $response->assertViewHas('mode', 'ferry');
        $response->assertViewHas('operator', '2GO Travel');
        $response->assertViewHas('isOperatorPreselected', true);
        $response->assertViewHas('isModePreselected', true);
        $response->assertViewHas('showOperatorConfirmation', true);
    }

    public function test_booking_form_disables_mode_when_pre_selected(): void
    {
        $response = $this->get('/book/new?operator=Cebu%20Pacific&trip_type=one_way&mode=airline');

        $response->assertStatus(200);
        // Check that the pre-selected fields are shown and disabled
        $response->assertSeeText('Pre-selected from your booking link', false);
    }

    public function test_booking_form_shows_confirmation_modal(): void
    {
        $response = $this->get('/book/new?operator=AirAsia&trip_type=one_way&mode=airline');

        $response->assertStatus(200);
        // The view should have showOperatorConfirmation set to true
        $response->assertViewHas('showOperatorConfirmation', true);
    }
}
