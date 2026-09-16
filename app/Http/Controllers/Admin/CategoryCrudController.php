<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup(): void
    {
        CRUD::setModel(Category::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/category');
        CRUD::setEntityNameStrings(singular: 'category', plural: 'categories');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
            'images' => 'required',
        ]);

        CRUD::field('name')->label('Label')->type('text');

        CRUD::field('parent_id')
            ->label('Parent')
            ->type('select')
            ->entity('parent');

        CRUD::field('large')
            ->label('Image')
            ->type('upload')
            ->fake(true)
            ->store_in('images')
            ->withFiles(true)
            ->hint(__('event.main_image_hint'));

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
        CRUD::enableReorder('name', 2);
    }
}
