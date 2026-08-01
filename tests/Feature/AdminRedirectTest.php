<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_admin_access_redirects_to_filament_login(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_unauthenticated_dashboard_access_redirects_to_normal_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_authenticated_only_on_web_guard_cannot_access_admin_without_admin_login(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
            'is_staff' => true,
        ]);

        // Logged in only on the web guard (e.g. customer website login)
        $response = $this->actingAs($user, 'web')->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_user_authenticated_on_admin_guard_can_access_admin(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
            'is_staff' => true,
        ]);

        $response = $this->actingAs($user, 'admin')->get('/admin');

        $response->assertStatus(200);
    }
}
