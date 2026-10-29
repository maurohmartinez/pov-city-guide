<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class UserFavouriteComposer
{
    public function compose(View $view): void
    {
        $view->with('userFavourites', auth()->check()
            ? auth()->user()->favourites()->get()->pluck('id')->toArray()
            : []
        );
    }
}
