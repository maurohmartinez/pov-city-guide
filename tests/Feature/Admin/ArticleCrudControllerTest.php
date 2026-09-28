<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\ArticleCrudController;
use App\Models\Article;
use App\Models\Category;
use JsonException;

class ArticleCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = ArticleCrudController::class;
    public string $model = Article::class;
    public string $route = 'article';

    public function test_create_page_loads_successfully(): void
    {
        $response = $this->get($this->testHelper->getCrudUrl('create'));
        $response->assertStatus(200);

        $fields = $this->testHelper->getOperationSetting('fields', [], 'create');
        foreach ($fields as $field) {
            $response->assertSee('name="' . $field['name'] . '"', false);
        }
    }

    /**
     * @throws JsonException
     */
    public function test_create_endpoint_adds_entry_to_database(): void
    {
        $this->skipIfModelDoesNotHaveFactory();

        $data = $this->model::factory()
            ->prepareImageForTesting()
            ->raw();
        $data['categories'] = [Category::factory()->create()->id];

        $response = $this->post($this->testHelper->getCrudUrl(), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        unset($assertInputFields['image']);

        $this->assertDatabaseHasModel($this->model, $assertInputFields);
    }

    public function test_create_endpoint_rejects_invalid_input(): void
    {
        $response = $this->post($this->testHelper->getCrudUrl());
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
