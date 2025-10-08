<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolesPermissionsSeeder;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure permissions and roles exist for views that call hasPermissionTo()
        // Keep this global seeding to avoid PermissionDoesNotExist exceptions in views
        // during rendering in many tests. If you prefer opt-in, remove these lines
        // and use the `Tests\Concerns\SeedsPermissions` trait in tests that need them.
        $this->seed(RolesPermissionsSeeder::class);

        // Clear Spatie permission cache so hasPermissionTo/hasRole reflect seeded data
        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
