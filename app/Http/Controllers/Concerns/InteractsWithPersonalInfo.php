<?php

namespace App\Http\Controllers\Concerns;

use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Support\Str;

trait InteractsWithPersonalInfo
{
    protected function ensureInfo(User $user): PersonalInfo
    {
        return $user->personalInfo()->firstOrCreate([], [
            'full_name' => $user->name,
            'email' => $user->email,
        ]);
    }

    protected function userCanManageProfiles(User $user): bool
    {
        $role = $user->role;

        if (! $role) {
            return false;
        }

        $allowed = [
            'admin',
            'administrator',
            'tchc',
            'director',
            'vicedirector'
        ];

        $roleSlugs = array_filter([
            $this->normalizeRole($role->name ?? null),
            $this->normalizeRole($role->label ?? null),
        ]);

        foreach ($roleSlugs as $slug) {
            if (in_array($slug, $allowed, true)) {
                return true;
            }
        }

        return false;
    }

    protected function normalizeRole(?string $value): ?string
    {
        return $value ? Str::slug(mb_strtolower($value)) : null;
    }
}