<?php

namespace App\Http\Controllers\Admin;

use App\Models\MenuItem;
use App\Models\Page;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class MenuItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup(): void
    {
        CRUD::setModel(MenuItem::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/menu-item');
        CRUD::setEntityNameStrings(singular: 'menu item', plural: 'menu items');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
        ]);

        CRUD::field('name')->label('Label')->type('text');

        CRUD::field('parent_id')
            ->label('Parent')
            ->type('select')
            ->entity('parent');

        CRUD::field('type,link')
            ->label('Type')
            ->type('page_or_link');

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
