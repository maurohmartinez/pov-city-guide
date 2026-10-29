<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\CategoryCrudController;
use App\Models\Category;
use JsonException;

class CategoryCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = CategoryCrudController::class;
    public string $model = Category::class;
    public string $route = 'category';

    public function test_create_page_loads_successfully(): void
    {
        $response = $this->get($this->testHelper->getCrudUrl('create'));
        $response->assertStatus(200);

        $response->assertSee('name="name"', false);
        $response->assertSee('name="template"', false);
    }

    /**
     * @throws JsonException
     */
    public function test_create_endpoint_adds_entry_to_database(): void
    {
        $this->skipIfModelDoesNotHaveFactory();

        $data = $this->model::factory()->raw();
        // `showInMenu` is a required fake field persisted into the extras JSON column.
        $data['showInMenu'] = 1;

        $response = $this->post($this->testHelper->getCrudUrl(), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        $this->assertDatabaseHasModel($this->model, $assertInputFields);

        $category = $this->model::where('slug', $data['slug'])->first();
        $this->assertEquals(1, $category->extras['showInMenu']);
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
        $response->assertSee('name="template"', false);
    }

    /**
     * @throws JsonException
     */
    public function test_update_endpoint_modifies_entry_in_database(): void
    {
        $this->skipIfModelDoesNotHaveFactory();

        $entry = $this->model::factory()->create();

        $data = $this->model::factory()->raw();
        $data['showInMenu'] = 1;
        $data[$entry->getKeyName()] = $entry->getKey();

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        $assertInputFields['id'] = $entry->getKey();
        // `slug` is derived from the name on create and isn't an editable field,
        // so it keeps the original value after an update.
        $assertInputFields['slug'] = $entry->slug;

        $this->assertDatabaseHasModel($this->model, $assertInputFields);
    }

    public function test_update_endpoint_rejects_invalid_input(): void
    {
        $this->skipIfModelDoesNotHaveFactory();

        $entry = $this->model::factory()->create();

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()));
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
