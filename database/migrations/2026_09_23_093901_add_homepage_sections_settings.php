<?php

use App\Models\Category;
use App\Services\SettingService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        DB::table(config('backpack.settings.table_name'))->insert(
            [
                'key' => SettingService::HOMEPAGE_SECTIONS,
                'name' => 'Homepage Sections',
                'description' => 'Choose what to display on the Homepage.',
                'value' => null,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Section',
                    'type' => 'repeatable',
                    'new_item_label' => 'Add section',
                    'reorder' => false,
                    'subfields' => [
                        [
                            'name' => 'category_id',
                            'label' => __('common.category'),
                            'type' => 'select2_from_ajax',
                            'attribute' => 'name',
                            'model' => Category::class,
                            'method' => 'POST',
                            'data_source' => backpack_url('article/fetch/category'),
                            'placeholder' => 'Select a category',
                            'minimum_input_length' => 0,
                            'validationRules' => 'required|exists:categories,id',
                            'validationMessages' => [
                                'required' => __('validation.required', ['attribute' => __('common.category')]),
                                'exists' => __('validation.exists', ['attribute' => __('common.category')]),
                            ],
                            'wrapper' => ['class' => 'form-group col-md-6'],
                        ],
                        [
                            'name' => 'layout_type',
                            'type' => 'select_from_array',
                            'label' => __('common.type'),
                            'options' => [
                                'cards-sm-carousel' => 'S cards carousel',
                                'cards-md-carousel' => 'M cards carousel',
                                'cards-lg' => 'L cards (max 4)',
                            ],
                            'allows_null' => false,
                            'wrapper' => ['class' => 'form-group col-md-6'],
                            'validationRules' => 'required|in:cards-sm,cards-md,cards-lg',
                        ],
                    ],
                ]),
                'active' => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table(config('backpack.settings.table_name'))
            ->where('key', SettingService::HOMEPAGE_SECTIONS)
            ->delete();
    }
};
