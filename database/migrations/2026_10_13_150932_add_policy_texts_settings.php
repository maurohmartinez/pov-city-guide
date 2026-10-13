<?php

use App\Services\SettingService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::table(config('backpack.settings.table_name'))->insert(
            [
                'key' => SettingService::TERMS_AND_CONDITIONS,
                'name' => __('common.terms_and_conditions'),
                'description' => 'Texts to show in terms and conditions.',
                'value' => null,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Text',
                    'type' => 'ckeditor',
                ]),
                'active' => 1,
            ],
        );

        DB::table(config('backpack.settings.table_name'))->insert(
            [
                'key' => SettingService::COOKIE_POLICY,
                'name' => __('common.cookie_policy'),
                'description' => 'Texts to show in terms and conditions.',
                'value' => null,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Text',
                    'type' => 'ckeditor',
                ]),
                'active' => 1,
            ],
        );

        DB::table(config('backpack.settings.table_name'))->insert(
            [
                'key' => SettingService::PRIVACY_POLICY,
                'name' => __('common.privacy_policy'),
                'description' => 'Texts to show in terms and conditions.',
                'value' => null,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Text',
                    'type' => 'ckeditor',
                ]),
                'active' => 1,
            ],
        );
    }

    public function down(): void
    {
        DB::table(config('backpack.settings.table_name'))->where('key', SettingService::TERMS_AND_CONDITIONS)->delete();
        DB::table(config('backpack.settings.table_name'))->where('key', SettingService::COOKIE_POLICY)->delete();
        DB::table(config('backpack.settings.table_name'))->where('key', SettingService::PRIVACY_POLICY)->delete();
    }
};
