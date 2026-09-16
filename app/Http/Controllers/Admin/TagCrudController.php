<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TagCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup(): void
    {
        CRUD::setModel(Tag::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/tag');
        CRUD::setEntityNameStrings(singular: 'tag', plural: 'tags');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
        ]);

        CRUD::field('name')->label('Label')->type('text');

        CRUD::autoTranslateConfirmationField();
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label('Label');

        CRUD::column('parent_id')->label('Parent')->type('select')->entity('parent');
    }

    protected function setupReorderOperation(): void
    {
        CRUD::enableReorder('name', 3);
    }
}
