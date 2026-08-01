<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('storage_asset_path')) {
    function storage_asset_path(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = (string) $path;

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if ($normalizedPath === '') {
            return null;
        }

        if (str_starts_with($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (str_starts_with($normalizedPath, 'public/')) {
            return Storage::disk('public')->url($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    }
}
