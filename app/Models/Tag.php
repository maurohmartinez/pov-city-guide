<?php

namespace App\Models;

use App\Traits\UseTranslatableToArray;
use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use App\Traits\HasCaseInsensitiveSearch;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'extras'])]
class Tag extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasTranslations, HasAutoTranslations;
    use UseTranslatableToArray, HasCaseInsensitiveSearch, Sluggable;

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
}
