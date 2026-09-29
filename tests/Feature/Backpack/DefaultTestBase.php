<?php

namespace Tests\Feature\Backpack;

use App\Models\User;
use Backpack\TestGenerators\CrudFeatureTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class DefaultTestBase extends CrudFeatureTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::first();
        $guard = config('backpack.base.guard') ?? config('auth.defaults.guard');

        $this->actingAs($user, $guard);
    }
}
