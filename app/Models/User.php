<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Booking;
use App\Models\UserLoginHistory;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_staff', 'is_admin', 'admin_permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    public const ADMIN_PERMISSIONS = [
        'manage_accommodations' => 'Accommodations',
        'manage_transport_classes' => 'Transport Classes',
        'manage_vehicle_rates' => 'Vehicle Rates',
        'manage_bookings' => 'Bookings',
        'manage_discounts' => 'Discounts',
        'manage_routes' => 'Routes',
        'manage_schedules' => 'Schedules',
        'manage_transactions' => 'Transactions',
        'manage_users' => 'Staff accounts',
        'manage_inquiries' => 'Inquiries',
        'manage_payment_settings' => 'Payment settings',
        'manage_website_settings' => 'Website settings',
        'manage_proofs' => 'Payment proofs',
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_staff' => 'boolean',
        'is_admin' => 'boolean',
        'admin_permissions' => 'array',
    ];

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isStaff(): bool
    {
        return (bool) $this->is_staff || (bool) $this->is_admin;
    }

    public function hasAdminPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->admin_permissions ?? [], true);
    }

    public function hasAnyAdminPermission(): bool
    {
        return $this->isSuperAdmin() || ! empty($this->admin_permissions);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (request()?->routeIs('*.auth.*')) {
            return true;
        }

        return $this->isStaff();
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(UserLoginHistory::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'client_email', 'email');
    }
}
