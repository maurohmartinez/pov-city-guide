<?php

namespace App\Models;

use App\Traits\UseTranslatableToArray;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use App\Traits\HasCaseInsensitiveSearch;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations, UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable;

    protected $fillable = ['name', 'slug', 'images', 'parent_id', 'lft', 'rgt', 'depth'];

    protected $casts = ['images' => 'array'];

    public array $translatable = ['name'];

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

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
