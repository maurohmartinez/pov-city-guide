<?php

namespace App\Http\Controllers;

use App\Enums\CategoryTemplateEnum;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\SettingService;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'sections' => array_map(function (array $value) {
                return [
                    'category' => Category::query()->whereId($value['category_id'])->firstOrFail(),
                    'layout_type' => $value['layout_type'],
                    'articles' => Article::query()
                        ->public()
                        ->with(['categoriesToDisplay', 'tags', 'author'])
                        ->whereHas('categories', fn ($query) => $query->where('categories.id', $value['category_id']))
                        ->limit(10)
                        ->orderBy('rgt')
                        ->get(),
                ];
            }, json_decode(Setting::get(SettingService::HOMEPAGE_SECTIONS), true) ?? []),
        ]);
    }

    public function article(Article $article): View
    {
        return view('article', [
            'article' => $article,
        ]);
    }

    public function category(Category $category): View
    {
        $template = $category->template;

        // Override template if category has no subcategories
        if ($template === CategoryTemplateEnum::WITH_SUBCATEGORIES) {
            $children = Category::query()
                ->where('parent_id', $category->id)
                ->where('id', array_column($category->extras['subcategories'], 'category_id'))
                ->get();

            $category->setRelation('children', $children);

            if (!$children) {
                $template = CategoryTemplateEnum::SIMPLE;
            }
        }

        return view('category.' . $template->value, [
            'category' => $category,
            'tags' => Tag::query()
                ->whereHas('articles', fn (Builder $query) => $query
                    ->public()
                    ->whereHas('categories', fn (Builder $q) => $q->whereKey($category->id))
                )
                ->get(),
            'relatedArticles' => Article::query()
                ->public()
                ->with(['tags', 'categoriesToDisplay', 'author'])
                ->whereDoesntHave('categories', fn ($query) => $query->where('categories.id', $category->id))
                ->inRandomOrder()
                ->limit(10)
                ->orderBy('rgt')
                ->get(),
        ]);
    }
}
