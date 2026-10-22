<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait HasCrudPermissions
{
    private function checkPermissions(array $permissions): void
    {
        foreach ($permissions as $permission => $actions) {
            if (!backpack_user()->hasPermissionTo($permission)) {
                CRUD::denyAccess($actions);
            }
        }
    }
}
