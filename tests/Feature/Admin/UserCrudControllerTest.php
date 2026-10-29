<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\UserCrudController;
use App\Models\User;

class UserCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = UserCrudController::class;
    public string $model = User::class;
    public string $route = 'user';

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
            'name' => 'Updated Customer Name',
            'email' => 'updated-customer-' . $entry->getKey() . '@example.com',
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
