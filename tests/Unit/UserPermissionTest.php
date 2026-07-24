<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    public function test_admin_accounts_have_access_to_every_feature(): void
    {
        $user = new User([
            'is_admin' => true,
            'is_staff' => true,
            'admin_permissions' => [],
        ]);

        $this->assertTrue($user->hasAdminPermission('bookings'));
        $this->assertTrue($user->hasAdminPermission('promotional_tickets'));
        $this->assertTrue($user->hasAdminPermission('overall_reports'));
    }

    public function test_legacy_permissions_are_normalized_to_the_current_feature_keys(): void
    {
        $user = new User([
            'is_admin' => false,
            'is_staff' => true,
            'admin_permissions' => ['manage_bookings', 'manage_users'],
        ]);

        $this->assertTrue($user->hasAdminPermission('bookings'));
        $this->assertTrue($user->hasAdminPermission('staff_accounts'));
        $this->assertFalse($user->hasAdminPermission('promotional_tickets'));
    }
}
