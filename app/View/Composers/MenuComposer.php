<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $view->with('categoriesMenuItems', Category::onlyParents()->onlyForMenu()->with('children')->get());
    }
}
