<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VisibilityEnum;
use App\Models\Article;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup(): void
    {
        CRUD::setModel(Article::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/article');
        CRUD::setEntityNameStrings(singular: 'article', plural: 'articles');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'title' => 'required|max:200',
            'content' => 'required|max:1000',
            'images' => 'required',
            'visibility' => 'required|in:' . VisibilityEnum::toString(),
        ]);

        CRUD::field('title')->label('Label')->type('text');

        CRUD::field('large')
            ->label('Image')
            ->type('upload')
            ->fake(true)
            ->store_in('images')
            ->withFiles(true)
            ->hint(__('event.main_image_hint'));

        CRUD::field('content')
            ->type('ckeditor')
            ->label('Content');

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
        CRUD::enableReorder('name', 0);
    }
}
