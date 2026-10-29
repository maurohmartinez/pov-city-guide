<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\VideoCrudController;
use App\Models\Category;
use App\Models\Video;
use JsonException;

class VideoCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = VideoCrudController::class;
    public string $model = Video::class;
    public string $route = 'video';

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
        // The "video" field submits its single video as a JSON string; posting the
        // raw array would be interpreted as nested params and fail the data.*.id rule.
        $videoData = $data['data'];
        $data['data'] = json_encode($videoData);

        $response = $this->post($this->testHelper->getCrudUrl(), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        // `image` is stored as an uploaded file path, `data` as a JSON array column
        // (both unmatched by the generic assertion) — assert `data` directly below.
        unset($assertInputFields['image'], $assertInputFields['data']);

        $this->assertDatabaseHasModel($this->model, $assertInputFields);

        $video = $this->model::latest('id')->first();
        $this->assertSame($videoData, $video->data);
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

        $fields = $this->testHelper->getOperationSetting('fields', [], 'update');
        foreach ($fields as $field) {
            $response->assertSee('name="' . $field['name'] . '"', false);
        }
    }

    /**
     * @throws JsonException
     */
    public function test_update_endpoint_modifies_entry_in_database(): void
    {
        $entry = $this->model::factory()->create();

        $data = $this->model::factory()
            ->prepareImageForTesting()
            ->raw();
        $data['categories'] = [Category::factory()->create()->id];
        $videoData = $data['data'];
        $data['data'] = json_encode($videoData);
        $data[$entry->getKeyName()] = $entry->getKey();

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        $assertInputFields['id'] = $entry->getKey();
        // `slug` is not an editable field (kept from the original title), and
        // `image`/`data` aren't matchable by the generic assertion.
        unset($assertInputFields['image'], $assertInputFields['data'], $assertInputFields['slug']);

        $this->assertDatabaseHasModel($this->model, $assertInputFields);

        $video = $this->model::find($entry->getKey());
        $this->assertSame($videoData, $video->data);
    }

    public function test_update_endpoint_rejects_invalid_input(): void
    {
        $entry = $this->model::factory()->create();

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()));
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
