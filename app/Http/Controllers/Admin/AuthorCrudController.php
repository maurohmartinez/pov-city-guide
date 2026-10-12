<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class AuthorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\Pro\Http\Controllers\Operations\InlineCreateOperation;

    public function setup(): void
    {
        CRUD::setModel(Author::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/author');
        CRUD::setEntityNameStrings(singular: __('common.author'), plural: __('common.authors'));
        CRUD::addBaseClause('withCount', 'articles');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('small_image')->label(__('common.image'))->type('image');
        CRUD::column('name')->label(__('common.name'));
        CRUD::column('description')->label(__('common.description'));
        CRUD::column('articles_count')->label(__('common.articles'));
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
            'image' => 'required',
            'description' => 'required|max:100',
        ]);

        CRUD::field('name')->label(__('common.name'))->type('text');
        CRUD::field('description')->label(__('common.description'))->type('text');

        CRUD::field('image')
            ->label(__('common.image'))
            ->type('image')
            ->withFiles(['disk' => 'authors'])
            ->crop(true)
            ->aspect_ratio(1);

        CRUD::autoTranslateConfirmationField();
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
