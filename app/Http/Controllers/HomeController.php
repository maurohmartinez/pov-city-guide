<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\SettingService;
use Backpack\Settings\app\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'articles' => Article::query()->with(['categories', 'tags'])->limit(5)->get(),
            'sections' => array_map(function (array $value) {
                return [
                    'category' => Category::find($value['category_id']),
                    'layout_type' => $value['layout_type'],
                    'articles' => Article::query()
                        ->with('categories', 'tags')
                        ->whereHas('categories', fn ($query) => $query->where('categories.id', $value['category_id']))
                        ->limit(10)
                        ->get(),
                ];
            }, json_decode(Setting::get(SettingService::HOMEPAGE_SECTIONS), true)),
        ]);
    }
}
