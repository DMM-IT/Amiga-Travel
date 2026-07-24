<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['admin_permissions'] = $this->resolveAdminPermissions($data);
        unset($data['staff_permissions']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['staff_permissions'] = self::buildPermissionState($data['admin_permissions'] ?? []);

        return $data;
    }

    protected function resolveAdminPermissions(array $data): array
    {
        if (! empty($data['is_admin'])) {
            return [];
        }

        return User::normalizePermissions(array_values(array_filter(
            array_map(
                fn (bool $selected, string $permission): ?string => $selected ? $permission : null,
                $data['staff_permissions'] ?? [],
                array_keys($data['staff_permissions'] ?? [])
            )
        )));
    }

    protected static function buildPermissionState(array $permissions): array
    {
        $state = [];

        foreach (array_keys(User::ADMIN_PERMISSIONS) as $permission) {
            $state[$permission] = false;
        }

        foreach (User::normalizePermissions($permissions) as $permission) {
            $state[$permission] = true;
        }

        return $state;
    }
}
