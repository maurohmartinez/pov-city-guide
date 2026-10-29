<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

trait RelatedTrait
{
    public function related(): Attribute
    {
        return new Attribute(
            get: function () {
                $collection = new Collection();
                $relatedByCategory = self::query()
                    ->whereHas('categoriesToDisplay', fn ($query) => $query->whereIn('categories.id', $this->categories->pluck('id')))
                    ->limit(5)
                    ->with(['categoriesToDisplay', 'tags', 'author'])
                    ->get();

                $relatedByTags = self::query()
                    ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $this->tags->pluck('id')))
                    ->limit(5)
                    ->with(['categoriesToDisplay', 'tags', 'author'])
                    ->get();

                $collection->push(...$relatedByCategory, ...$relatedByTags);

                return $collection->shuffle();
            },
        );
    }
}
