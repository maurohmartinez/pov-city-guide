<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryTemplateEnum;
use App\Models\Category;
use App\Services\RoleAndPermissionService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryCrudController extends CrudController
{
    use \App\Traits\HasCrudPermissions;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\Pro\Http\Controllers\Operations\FetchOperation;

    public function setup(): void
    {
        CRUD::setModel(Category::class);
        CRUD::setRoute(route: config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings(singular: __('common.category'), plural: __('common.categories'));
        CRUD::addBaseClause('withCount', 'articles');

        $this->checkPermissions([
            RoleAndPermissionService::PERMISSION_CATEGORY_READ => ['list'],
            RoleAndPermissionService::PERMISSION_CATEGORY_CREATE => ['create'],
            RoleAndPermissionService::PERMISSION_CATEGORY_UPDATE => ['update', 'reorder'],
            RoleAndPermissionService::PERMISSION_CATEGORY_DELETE => ['delete'],
        ]);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label(__('common.name'));
        CRUD::column('template')->label(__('common.template'))->type('enum');
        CRUD::column('articles_count')->label(__('common.articles'));
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:100',
            'template' => 'required|in:' . CategoryTemplateEnum::toString(),
            'description_top' => 'sometimes|nullable|max:2000',
            'description_bottom' => 'sometimes|nullable|max:2000',
            'showInMenu' => 'required|boolean',
            'subcategories.*.category_id' => 'sometimes|nullable|exists:categories,id',
        ]);

        CRUD::field('name')->label(__('common.name'))->type('text')->size(6);
        CRUD::field('template')->label(__('common.template'))->size(6)->type('enum');

        CRUD::field('description_top')->label(__('common.description_top'))->type('textarea')->fake(true);
        CRUD::field('description_bottom')->label(__('common.description_bottom'))->type('textarea')->fake(true);

        CRUD::field('hint_html')->type('custom_html')
            ->value('<div class="alert alert-info d-flex align-items-center"><i class="la la-warning"></i>.' . __('common.template_hint') . '</div>');

        CRUD::field('subcategories')
            ->label(__('common.subcategories'))
            ->type('repeatable')
            ->subfields([
                [
                    'name' => 'category_id',
                    'label' => __('common.category'),
                    'type' => 'select2_from_ajax',
                    'entity' => 'children',
                    'attribute' => 'name',
                    'data_source' => backpack_url('category/fetch/children'),
                    'minimum_input_length' => 0,
                    'include_all_form_fields' => true,
                    'method' => 'POST',
                ]
            ])
            ->fake(true);

        CRUD::field('showInMenu')->label(__('common.show_in_menu'))->type('switch')->fake(true);

        Widget::add()->type('script')->content(asset('js/admin/forms/category.js'));

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

    protected function fetchChildren(): LengthAwarePaginator
    {
        $subcategories = array_column(backpack_form_input()['subcategories'] ?? [], 'category_id');

        return Category::query()
            ->where('parent_id', (int)backpack_form_input()['id'])
            ->when(count($subcategories) > 0, fn (Builder $query) => $query->whereNotIn('id', $subcategories))
            ->paginate(10);
    }
}
