<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserController extends Controller
{
    public function profile(): View
    {
        return view('auth.profile');
    }

    public function favourites(): View
    {
        return view('auth.favourites', [
            'articles' => auth()
                ->user()
                ->favourites()
                ->withCount('favourites')
                ->with(['categories', 'tags', 'author'])
                ->get(),
        ]);
    }
}
