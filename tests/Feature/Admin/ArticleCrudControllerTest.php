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
        // The create form exposes the author relation under the "author" field,
        // which Backpack maps to author_id. Posting author_id directly would be
        // stripped as an undefined field, so send it under the form field name.
        $data['author'] = $data['author_id'];

        $response = $this->post($this->testHelper->getCrudUrl(), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        // `image` is stored as an uploaded file path, not the raw base64 input.
        // `content` is a translatable column holding a nested array, which the
        // generic JSON-path assertion can't match — assert it directly below.
        unset($assertInputFields['image'], $assertInputFields['content']);

        $this->assertDatabaseHasModel($this->model, $assertInputFields);

        $article = $this->model::latest('id')->first();
        $this->assertSame($data['content'], $article->getTranslation('content', app()->getLocale()));
    }

    public function test_create_endpoint_rejects_invalid_input(): void
    {
        $response = $this->post($this->testHelper->getCrudUrl());
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
