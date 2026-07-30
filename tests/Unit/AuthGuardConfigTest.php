<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthGuardConfigTest extends TestCase
{
    public function test_admin_guard_is_registered_and_uses_the_web_provider(): void
    {
        $guard = Auth::guard('admin');

        $this->assertNotNull($guard);
        $this->assertSame('users', config('auth.guards.admin.provider'));
        $this->assertSame(\App\Models\User::class, $guard->getProvider()->getModel());
    }
}
