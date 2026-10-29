<?php

namespace Tests\Feature\Backpack;

use App\Models\Admin;
use App\Services\RoleAndPermissionService;
use Backpack\TestGenerators\CrudFeatureTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class DefaultTestBase extends CrudFeatureTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Migrations seed permissions in several steps; drop Spatie's cached
        // permission list so lookups reflect the fully-migrated database.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('backpack.base.guard') ?? config('auth.defaults.guard');

        $user = Admin::factory()->create();
        $user->roles()->attach(Role::findByName(RoleAndPermissionService::ROLE_SUPERADMIN, $guard));

        $this->actingAs($user, $guard);
    }
}
