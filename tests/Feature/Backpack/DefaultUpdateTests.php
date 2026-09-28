<?php

namespace Tests\Feature\Backpack;

use App\Traits\HasImages;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use JsonException;

trait DefaultUpdateTests
{
    public function test_update_page_loads_successfully(): void
    {
        $this->skipIfModelDoesNotHaveFactory();

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
        $this->skipIfModelDoesNotHaveFactory();

        $entry = $this->model::factory()->create();
        $data = $this->model::factory()
            ->when(in_array(HasImages::class, class_uses_recursive($this->model), true), fn (Factory $factory) => $factory->prepareImageForTesting())
            ->when(in_array(Sluggable::class, class_uses_recursive($this->model), true), fn (Factory $factory) => $factory->state(new Sequence(
                ['slug' => $entry->slug],
            )))
            ->raw();

        $data = array_merge($data, [
            $entry->getKeyName() => $entry->getKey(),
        ]);

        $response = $this->put($this->testHelper->getCrudUrl($entry->getKey()), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);
        $assertInputFields['id'] = $entry->getKey();

        // If the model used the HasImages trait, strip away the image field to find the record
        if (in_array(HasImages::class, class_uses_recursive($this->model), true)) {
            unset($assertInputFields['image']);
        }

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
