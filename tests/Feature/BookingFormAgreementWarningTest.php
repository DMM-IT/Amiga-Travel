<?php

namespace Tests\Feature;

use App\Livewire\BookingForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingFormAgreementWarningTest extends TestCase
{
    use RefreshDatabase;
    public function test_submitting_without_accepting_terms_shows_the_warning_in_the_terms_modal(): void
    {
        Livewire::test(BookingForm::class)
            ->set('client_name', 'Jane Doe')
            ->set('client_email', 'jane@example.com')
            ->set('client_phone', '09171234567')
            ->call('submit')
            ->assertSet('showTermsModal', true)
            ->assertSee('You need to read and agree to continue.');
    }
}
