<?php

namespace App\Traits;

use App\Database\CaseInsensitiveBuilder;

/**
 * Trait to enable case-insensitive search for translatable JSON columns.
 *
 * Add this trait to any model that uses spatie/laravel-translatable to make
 * LIKE searches case-insensitive. Fixes the issue where searching "bucharest"
 * wouldn't find "Bucharest" in Backpack CRUD interfaces and other searches.
 */
trait HasCaseInsensitiveSearch
{
    /**
     * Create a new Eloquent query builder for the model that supports
     * case-insensitive searches for translatable JSON columns.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \App\Database\CaseInsensitiveBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new CaseInsensitiveBuilder($query);
    }

}
