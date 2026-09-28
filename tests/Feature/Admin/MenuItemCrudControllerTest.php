<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\MenuItemCrudController;
use App\Models\MenuItem;

class MenuItemCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultCreateTests;
    use \Tests\Feature\Backpack\DefaultUpdateTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = MenuItemCrudController::class;
    public string $model = MenuItem::class;
    public string $route = 'menu-item';
}
