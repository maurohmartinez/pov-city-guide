<?php

namespace Tests\Feature\Backpack;

use App\Models\Admin;
use App\Services\RoleAndPermissionService;
use Backpack\TestGenerators\CrudFeatureTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

abstract class DefaultTestBase extends CrudFeatureTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $guard = config('backpack.base.guard') ?? config('auth.defaults.guard');

        $user = Admin::factory()->create();
        $user->roles()->attach(Role::findByName(RoleAndPermissionService::ROLE_SUPERADMIN, $guard));

        $this->actingAs($user, $guard);
    }
}
