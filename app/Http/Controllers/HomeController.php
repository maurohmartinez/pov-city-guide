<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'articles' => Article::query()->with(['categories', 'tags'])->limit(5)->get(),
        ]);
    }
}
