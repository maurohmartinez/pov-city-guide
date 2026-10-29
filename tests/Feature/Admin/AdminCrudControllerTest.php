<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\AdminCrudController;
use App\Models\Admin;

class AdminCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = AdminCrudController::class;
    public string $model = Admin::class;
    public string $route = 'admin';

    public function test_create_page_loads_successfully(): void
    {
        $response = $this->get($this->testHelper->getCrudUrl('create'));
        $response->assertStatus(200);

        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
    }

    public function test_create_endpoint_adds_entry_to_database(): void
    {
        $data = $this->model::factory()->raw();
        $data['password'] = 'secret-password';
        $data['password_confirmation'] = 'secret-password';

        $response = $this->post($this->testHelper->getCrudUrl(), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHasModel($this->model, [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_create_endpoint_rejects_invalid_input(): void
    {
        $response = $this->post($this->testHelper->getCrudUrl());
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    public function test_update_page_loads_successfully(): void
    {
        $entry = $this->model::factory()->create();

        $response = $this->get($this->testHelper->getCrudUrl($entry->getKey() . '/edit'));
        $response->assertStatus(200);

        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
    }

    public function test_update_endpoint_modifies_entry_in_database(): void
    {
        $entry = $this->model::factory()->create();

        $data = [
            'id' => $entry->getKey(),
            'name' => 'Updated Admin Name',
            'email' => 'updated-admin-' . $entry->getKey() . '@example.com',
        ];

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHasModel($this->model, [
            'id' => $entry->getKey(),
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_update_endpoint_rejects_invalid_input(): void
    {
        $entry = $this->model::factory()->create();

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()));
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
