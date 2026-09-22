<?php

namespace App\Models;

use App\Enums\VisibilityEnum;
use App\Observers\ArticleObserver;
use App\Traits\HasCaseInsensitiveSearch;
use App\Traits\HasImages;
use App\Traits\UseTranslatableToArray;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ArticleObserver::class)]
class Article extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations;
    use UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable, HasImages;

    protected $fillable = ['title', 'content', 'slug', 'image', 'visibility', 'parent_id', 'lft', 'rgt', 'depth', 'extras'];

    public array $translatable = ['title', 'content'];

    protected $casts = ['visibility' => VisibilityEnum::class, 'extras' => 'array'];

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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
