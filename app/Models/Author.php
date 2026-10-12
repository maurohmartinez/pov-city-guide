<?php

namespace App\Models;

use App\Observers\AuthorObserver;
use App\Traits\HasImages;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Traits\HasCaseInsensitiveSearch;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(AuthorObserver::class)]
#[Fillable(['name', 'slug', 'description', 'image'])]
class Author extends Model
{
    use HasFactory, SoftDeletes, CrudTrait, HasCaseInsensitiveSearch, Sluggable, HasImages;

    protected $casts = ['extras' => 'array'];

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
