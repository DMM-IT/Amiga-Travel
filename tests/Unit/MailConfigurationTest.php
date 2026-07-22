<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mail_defaults_to_smtp_when_credentials_are_present(): void
    {
        putenv('MAIL_MAILER=');
        putenv('MAIL_HOST=smtp.gmail.com');
        putenv('MAIL_PORT=587');
        putenv('MAIL_USERNAME=test@example.com');
        putenv('MAIL_PASSWORD=secret');
        putenv('MAIL_ENCRYPTION=tls');
        putenv('MAIL_FROM_ADDRESS=');
        putenv('MAIL_FROM_NAME=');

        $this->refreshApplication();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.gmail.com', config('mail.mailers.smtp.host'));
        $this->assertSame('test@example.com', config('mail.from.address'));
    }
}
