<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\TagCrudController;
use App\Models\Tag;

class TagCrudControllerTest extends \Tests\Feature\Backpack\DefaultTestBase
{
    use \Tests\Feature\Backpack\DefaultListTests;
    use \Tests\Feature\Backpack\DefaultCreateTests;
    use \Tests\Feature\Backpack\DefaultUpdateTests;
    use \Tests\Feature\Backpack\DefaultDeleteTests;

    public string $controller = TagCrudController::class;
    public string $model = Tag::class;
    public string $route = 'tag';
}
