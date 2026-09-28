<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\CategoryCrudController;
use App\Models\Category;

class CategoryCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultCreateTests;
    use \Tests\Feature\Backpack\DefaultUpdateTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = CategoryCrudController::class;
    public string $model = Category::class;
    public string $route = 'category';
}
