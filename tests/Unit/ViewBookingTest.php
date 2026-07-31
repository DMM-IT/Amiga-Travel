<?php

namespace Tests\Unit;

use App\Filament\Resources\BookingResource\Pages\ViewBooking;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

class ViewBookingTest extends TestCase
{
    public function test_id_image_placeholder_handles_null_state(): void
    {
        $page = new ViewBooking();
        $method = new \ReflectionMethod($page, 'renderPassengerIdLinkContent');
        $method->setAccessible(true);

        $result = $method->invoke($page, null);

        $this->assertInstanceOf(HtmlString::class, $result);
        $this->assertStringContainsString('No image', (string) $result);
    }

    public function test_proof_placeholder_handles_missing_path(): void
    {
        $page = new ViewBooking();
        $method = new \ReflectionMethod($page, 'renderProofImageContent');
        $method->setAccessible(true);

        $result = $method->invoke($page, null);

        $this->assertInstanceOf(HtmlString::class, $result);
        $this->assertStringContainsString('No proof uploaded', (string) $result);
    }
}
