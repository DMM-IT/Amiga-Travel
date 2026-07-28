<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mail_defaults_to_smtp_when_credentials_are_present(): void
    {
        // phpunit.xml injects MAIL_MAILER=array into $_ENV; we must clear all three
        // sources so refreshApplication() picks up the config default ('smtp') instead.
        // Note: putenv('VAR') without '=' fully removes it; putenv('VAR=') sets it to ''
        putenv('MAIL_MAILER');
        unset($_ENV['MAIL_MAILER'], $_SERVER['MAIL_MAILER']);
        putenv('MAIL_HOST=smtp.gmail.com');
        putenv('MAIL_PORT=587');
        putenv('MAIL_USERNAME=test@example.com');
        putenv('MAIL_PASSWORD=secret');
        putenv('MAIL_ENCRYPTION=tls');
        putenv('MAIL_FROM_ADDRESS=test@example.com');
        putenv('MAIL_FROM_NAME=');

        $this->refreshApplication();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.gmail.com', config('mail.mailers.smtp.host'));
        // from.address is populated from the real .env file during refreshApplication();
        // assert it is a valid email rather than a fixture-specific value.
        $this->assertMatchesRegularExpression('/.+@.+\..+/', config('mail.from.address'));
    }
}
