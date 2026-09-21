<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Validation\Rules\ValidUpload;

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
        CRUD::addBaseClause('withCount', 'articles');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('small_image')->label('Image')->type('image');
        CRUD::column('name')->label('Label');
        CRUD::column('articles_count')->label('Articles');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
            'image' => 'required',
        ]);

        CRUD::field('name')->label('Name')->type('text');

        CRUD::field('image')
            ->label('Image')
            ->type('image')
            ->withFiles(['disk' => 'categories'])
            ->crop(true)
            ->aspect_ratio(16/9)
            ->hint('Ideal size 2400×800px.');

        CRUD::autoTranslateConfirmationField();
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupReorderOperation(): void
    {
        CRUD::enableReorder('name', 2);
    }
}
