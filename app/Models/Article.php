<?php

namespace App\Models;

use App\Enums\VisibilityEnum;
use App\Traits\HasCaseInsensitiveSearch;
use App\Traits\UseTranslatableToArray;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations, UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable;

    protected $fillable = ['title', 'content', 'slug', 'images', 'visibility', 'parent_id', 'lft', 'rgt', 'depth', 'extras'];

    public array $translatable = ['title', 'content'];

    protected $casts = ['visibility' => VisibilityEnum::class, 'images' => 'array', 'extras' => 'array'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
