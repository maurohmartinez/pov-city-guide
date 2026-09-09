<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use App\Traits\HasCaseInsensitiveSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends \Backpack\PageManager\app\Models\Page
{
    use HasFactory, HasTranslations, HasAutoTranslations, HasCaseInsensitiveSearch;

    public array $translatable = ['title', 'content', 'extras_translatable'];

    protected $fillable = ['template', 'name', 'title', 'slug', 'content', 'extras', 'extras_translatable'];

    protected $fakeColumns = ['extras', 'extras_translatable'];

    protected $casts = ['extras' => 'array', 'extras_translatable' => 'array'];
}
