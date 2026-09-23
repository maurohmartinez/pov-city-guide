<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VisibilityEnum;
use App\Models\Article;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\Pro\Http\Controllers\Operations\FetchOperation;

    public function setup(): void
    {
        CRUD::setModel(Article::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings(singular: __('common.article'), plural: __('common.articles'));
    }

    protected function setupListOperation(): void
    {
        CRUD::column('small_image')->label(__('common.image'))->type('image');
        CRUD::column('title')->label(__('common.title'));
        CRUD::column('categories')->label(__('common.categories'));
        CRUD::column('tags')->label(__('common.tags'));
        CRUD::column('visibility')->label(__('common.visibility'))
            ->type('enum')
            ->enum(VisibilityEnum::cases())
            ->wrapper([
                'element' => 'span',
                'class' => function (CrudPanel $crud, array $column, Article $entry) {
                    return match ($entry->visibility) {
                        VisibilityEnum::PUBLIC => 'badge bg-success-lt fs-6 py-1',
                        VisibilityEnum::PRIVATE => 'badge bg-orange-lt fs-6 py-1',
                        VisibilityEnum::HIDDEN => 'badge bg-dark-lt fs-6 py-1',
                    };
                },
            ]);
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'title' => 'required|max:200',
            'content' => 'required|max:3000',
            'image' => 'required',
            'visibility' => 'required|in:' . VisibilityEnum::toString(),
            'categories' => 'required|exists:categories,id',
            'tags' => 'sometimes|nullable|exists:tags,id',
        ]);

        CRUD::field('title')->label(__('common.title'));
        CRUD::field('categories')->label(__('common.categories'))->size(6);
        CRUD::field('tags')->label(__('common.tags'))->size(6);

        CRUD::field('image')
            ->label(__('common.image'))
            ->type('image')
            ->withFiles(['disk' => 'articles'])
            ->crop(true)
//            ->aspect_ratio(16 / 9)
            ->hint('Ideal size 2400×800px.');

        CRUD::field('content')
            ->type('ckeditor')
            ->label(__('common.content'));

        CRUD::field('visibility')
            ->type('enum')
            ->label(__('common.visibility'))
            ->default(VisibilityEnum::PRIVATE);

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

    protected function fetchCategory(): JsonResponse|Paginator
    {
        return $this->fetch(Category::class);
    }
}
