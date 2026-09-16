<?php

use Prologue\Alerts\Facades\Alert;
use Backpack\AutoTranslate\Facades\AutoTranslate;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

// Register CrudPanel macro for auto-translate field
CrudPanel::macro('autoTranslateConfirmationField', function (array $relations = []) {

    if (!AutoTranslate::isReady()) {
        return CRUD::field('_auto_translate_confirmation')->type('hidden')->value(false);
    }

    return CRUD::field('_auto_translate_confirmation')
        ->type('checkbox')
        ->label(__('auto-translate.field_label'))
        ->default(true)
        ->fake(true)
        ->on('saved', function ($entry) use ($relations) {
            if (request()->input('_auto_translate_confirmation')) {
                $success = AutoTranslate::translate(
                    model: $entry,
                    overwrite: true,
                    immediate: false,
                    cascade: $relations
                );

                if ($success) {
                    Alert::add('info', __('auto-translate.success_message'))->flash();
                } else {
                    Alert::add('warning', __('auto-translate.error_message'))->flash();
                }
            }
        })->wrapperAttributes([
            'class' => 'form-group', // removes the standard .mb-3
        ]);
});
