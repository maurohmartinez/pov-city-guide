<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table(config('backpack.settings.table_name'))->insert(
            [
                'key'         => 'social_media_links',
                'name'        => 'Social Media Links',
                'description' => 'Social media links to display.',
                'value'       => null,
                'field'       => json_encode([
                    'name' => 'value',
                    'label' => 'Links',
                    'type' => 'repeatable',
                    'new_item_label' => 'Add link',
                    'reorder' => false,
                    'subfields' => [
                        [
                            'name' => 'type',
                            'label' => 'Type',
                            'type' => 'select2_from_array',
                            'options' => [
                                'instagram' => 'Instagram',
                                'facebook' => 'Facebook',
                                'x' => 'X',
                                'tiktok' => 'TikTok',
                                'youtube' => 'YouTube',
                                'vimeo' => 'Vimeo',
                            ],
                            'wrapper' => ['class' => 'form-group col-md-5'],
                        ],
                        [
                            'name' => 'link',
                            'label' => 'Link',
                            'type' => 'url',
                            'wrapper' => ['class' => 'form-group col-md-7'],
                        ],
                    ],
                ]),
                'active'      => 1,
            ]
        );
    }

    public function down(): void
    {
        DB::table(config('backpack.settings.table_name'))
            ->where('key', 'social_media_links')
            ->delete();
    }
};
