<?php

namespace App\Models;

use App\Enums\VisibilityEnum;
use App\Observers\VideoObserver;
use App\Traits\HasCaseInsensitiveSearch;
use App\Traits\HasImages;
use App\Traits\RelatedTrait;
use App\Traits\UseTranslatableToArray;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(VideoObserver::class)]
#[Fillable([
    'title',
    'description',
    'slug',
    'data',
    'image',
    'visibility',
    'parent_id',
    'lft',
    'rgt',
    'depth',
    'extras',
])]
class Video extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations;
    use UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable, HasImages, RelatedTrait;

    public array $translatable = ['title', 'description'];

    protected $casts = ['visibility' => VisibilityEnum::class, 'data' => 'array', 'extras' => 'array'];

    protected array $fakeColumns = ['extras'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function categoriesToDisplay(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->whereDoesntHave('children');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('visibility', VisibilityEnum::PUBLIC);
    }

    public function scopePrivate(Builder $query)
    {
        return $query->where('visibility', VisibilityEnum::PRIVATE);
    }

    public function scopeHidden(Builder $query)
    {
        return $query->where('visibility', VisibilityEnum::HIDDEN);
    }
}
