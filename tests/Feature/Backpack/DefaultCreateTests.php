<?php

namespace Tests\Feature\Backpack;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

trait DefaultCreateTests
{
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
            ->when(in_array(HasImages::class, class_uses_recursive($this->model), true), fn (Factory $factory) => $factory->prepareImageForTesting())
            ->raw();

        $response = $this->post($this->testHelper->getCrudUrl(), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $assertInputFields = $this->testHelper->getDatabaseAssertInput($this->model, $data);

        // If the model used the HasImages trait, strip away the image field to find the record
        if (in_array(HasImages::class, class_uses_recursive($this->model), true)) {
            unset($assertInputFields['image']);
        }

        $this->assertDatabaseHasModel($this->model, $assertInputFields);
    }

    public function test_create_endpoint_rejects_invalid_input(): void
    {
        $response = $this->post($this->testHelper->getCrudUrl());
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
