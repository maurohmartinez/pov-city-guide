<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\AuthorCrudController;
use App\Models\Author;

class AuthorCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultCreateTests;
    use \Tests\Feature\Backpack\DefaultUpdateTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = AuthorCrudController::class;
    public string $model = Author::class;
    public string $route = 'author';
}
