<?php

namespace Tests\Concerns;

trait SeedsPermissions
{
    /**
     * Seed roles and permissions and clear Spatie cache.
     */
    protected function seedPermissions(): void
    {
        // Use the project's seeder for roles/permissions
        if (class_exists(\Database\Seeders\RolesPermissionsSeeder::class)) {
            $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);
        }

        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
