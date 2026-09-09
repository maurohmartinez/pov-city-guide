<?php

namespace App\Models;

use Backpack\AutoTranslate\Traits\HasAutoTranslations;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Backpack\PageManager\app\Models\Page;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use CrudTrait, HasFactory, HasTranslations, HasAutoTranslations, SoftDeletes;

    protected $table = 'menu_items';

    protected $fillable = ['name', 'type', 'link', 'page_id', 'parent_id'];

    public array $translatable = ['name'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->with('children', 'page');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public static function getTree(): Collection
    {
        return self::whereNull('parent_id')->orderBy('lft')->with('children', 'page')->get();
    }

    public function url(): Attribute
    {
        return Attribute::make(get: function () {
            return match ($this->type) {
                'external_link' => $this->link,
                'internal_link' => is_null($this->link) ? '#' : url($this->link),
                default => $this->page ? url($this->page->slug) : null,
            };
        });
    }
}
