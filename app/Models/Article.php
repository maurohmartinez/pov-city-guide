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
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

#[ObservedBy(ArticleObserver::class)]
#[Fillable(['title', 'content', 'description', 'slug', 'image', 'visibility', 'parent_id', 'lft', 'rgt', 'depth', 'extras'])]
class Article extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations;
    use UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable, HasImages;

    public array $translatable = ['title', 'content'];

    protected $casts = ['visibility' => VisibilityEnum::class, 'extras' => 'array'];

    protected array $fakeColumns = ['extras'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * The dynamic_content field submits its rows as a JSON string. Decode it to an array
     * so Spatie stores clean nested JSON ({"en":[{...}]}) instead of an escaped string
     * ({"en":"[{\"type\":...}]"}). Non-JSON values (e.g. plain text) are left untouched.
     */
    public function setContentAttribute(mixed $value, ?string $locale = null): void
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                $value = $decoded;
            }
        }

        $this->attributes['content'] = $value;
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

    public function related(): Attribute
    {
        return new Attribute(
            get: function () {
                $collection = new Collection();
                $relatedByCategory = self::query()
                    ->whereHas('categories', fn ($query) => $query->whereIn('categories.id', $this->categories->pluck('id')))
                    ->limit(5)
                    ->with('categories', 'tags')
                    ->get();

                $relatedByTags = self::query()
                    ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $this->tags->pluck('id')))
                    ->limit(5)
                    ->with('categories', 'tags')
                    ->get();

                $collection->push(...$relatedByCategory, ...$relatedByTags);

                return $collection->shuffle();
            },
        );
    }
}
