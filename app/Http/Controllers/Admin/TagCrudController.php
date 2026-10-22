<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Services\RoleAndPermissionService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TagCrudController extends CrudController
{
    use \App\Traits\HasCrudPermissions;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup(): void
    {
        CRUD::setModel(Tag::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/tag');
        CRUD::setEntityNameStrings(singular: __('common.tag'), plural: __('common.tags'));
        CRUD::addBaseClause('withCount', 'articles');

        $this->checkPermissions([
            RoleAndPermissionService::PERMISSION_TAG_READ => ['list'],
            RoleAndPermissionService::PERMISSION_TAG_CREATE => ['create'],
            RoleAndPermissionService::PERMISSION_TAG_UPDATE => ['update'],
            RoleAndPermissionService::PERMISSION_TAG_DELETE => ['delete'],
        ]);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label(__('common.name'));
        CRUD::column('articles_count')->label(__('common.articles'));
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
        ]);

        CRUD::field('name')->label(__('common.name'))->type('text');

        CRUD::autoTranslateConfirmationField();
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupReorderOperation(): void
    {
        CRUD::enableReorder('name', 3);
    }
}
