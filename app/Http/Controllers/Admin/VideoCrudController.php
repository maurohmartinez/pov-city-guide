<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VisibilityEnum;
use App\Models\Video;
use App\Services\RoleAndPermissionService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class VideoCrudController extends CrudController
{
    use \App\Traits\HasCrudPermissions;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup(): void
    {
        CRUD::setModel(Video::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix').'/video');
        CRUD::setEntityNameStrings(singular: 'Video', plural: 'Videos');

        $this->checkPermissions([
            RoleAndPermissionService::PERMISSION_VIDEO_READ => ['list'],
            RoleAndPermissionService::PERMISSION_VIDEO_CREATE => ['create'],
            RoleAndPermissionService::PERMISSION_VIDEO_UPDATE => ['update', 'reorder'],
            RoleAndPermissionService::PERMISSION_VIDEO_DELETE => ['delete'],
        ]);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('small_image')->label(__('common.image'))->type('image');
        CRUD::column('title')->label(__('common.title'));
        CRUD::column('description')->label(__('common.description'));
        CRUD::column('categories')->label(__('common.categories'));
        CRUD::column('tags')->label(__('common.tags'));
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'title' => 'required|max:100',
            'description' => 'sometimes|nullable|max:255',
            'data' => 'required',
            'data.*.id' => 'required',
            'image' => 'required',
            'visibility' => 'required|in:' . VisibilityEnum::toString(),
            'categories' => 'required|exists:categories,id',
            'tags' => 'sometimes|nullable|exists:tags,id',
        ]);

        CRUD::field('title')->label(__('common.title'))->type('text');
        CRUD::field('description')->label(__('common.description'))->type('text');
        CRUD::field('data')->label('URL')->type('video');
        CRUD::field('categories')->label(__('common.categories'))->size(6)->tab('General');
        CRUD::field('tags')->label(__('common.tags'))->size(6)->tab('General')->inline_create(['entity' => 'tag']);

        CRUD::field('image')
            ->label(__('common.image'))
            ->type('image')
            ->withFiles(['disk' => 'videos'])
            ->crop(true)
            ->aspect_ratio(16/9);

        CRUD::field('visibility')
            ->type('enum')
            ->label(__('common.visibility'))
            ->default(VisibilityEnum::PRIVATE)
            ->tab('General');

        CRUD::autoTranslateConfirmationField();
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupReorderOperation(): void
    {
        CRUD::enableReorder('title', 1);
    }
}
