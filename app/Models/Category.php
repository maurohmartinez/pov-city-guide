<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use App\Traits\HasImages;
use App\Traits\UseTranslatableToArray;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use App\Traits\HasCaseInsensitiveSearch;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(CategoryObserver::class)]
#[Fillable(['name', 'slug', 'image', 'parent_id', 'lft', 'rgt', 'depth', 'extras'])]
class Category extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations;
    use UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable, HasImages;

    public array $translatable = ['name'];

    protected $casts = ['extras' => 'array'];

    protected array $fakeColumns = ['extras'];

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

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories', 'parent_id', 'id');
    }

    public function scopeOnlyParents(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    public function scopeOnlyChildren(Builder $query): void
    {
        $query->whereNotNull('children');
    }

    public function scopeOnlyForMenu(Builder $query): void
    {
        $query->where('extras->showInMenu', true);
    }
}
