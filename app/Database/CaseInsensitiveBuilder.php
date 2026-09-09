<?php

namespace App\Database;

use App\Services\LocalizationService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Custom Eloquent Builder that makes LIKE searches case-insensitive for translatable JSON columns.
 *
 * Problem: spatie/laravel-translatable stores translations as JSON (e.g., {"en": "Bucharest", "ro": "București"}).
 * By default, MySQL JSON searches are case-sensitive, so searching "bucharest" wouldn't find "Bucharest".
 * This was particularly problematic in Backpack CRUD interfaces where relationship fields (like City in Venue)
 * became unusable due to case-sensitive search behavior.
 *
 * Solution: Override where() and orWhere() methods to automatically detect translatable JSON columns
 * and apply case-insensitive LOWER() functions to both the JSON values and search terms.
 *
 * Usage: Add HasCaseInsensitiveSearch trait to any translatable model.
 *
 * Safety: Only affects LIKE operations on translatable columns. Exact matches remain case-sensitive.
 */
class CaseInsensitiveBuilder extends Builder
{
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        // Handle Laravel's shorthand where($column, $value) syntax
        if (func_num_args() === 2 && is_null($operator)) {
            $value = $operator;
            $operator = '=';
        } elseif (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        // Check if this should be a case-insensitive translatable search
        if ($this->shouldUseTranslatableSearch($column, $operator, $value)) {
            return $this->whereTranslatableJson($column, $value, $boolean);
        }

        return parent::where($column, $operator, $value, $boolean);
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        // Handle Laravel's shorthand orWhere($column, $value) syntax
        if (func_num_args() === 2 && is_null($operator)) {
            $value = $operator;
            $operator = '=';
        } elseif (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        // Check if this should be a case-insensitive translatable search
        if ($this->shouldUseTranslatableSearch($column, $operator, $value)) {
            return $this->whereTranslatableJson($column, $value, 'or');
        }

        return parent::orWhere($column, $operator, $value);
    }


    /**
     * Determine if we should use case-insensitive translatable search
     */
    protected function shouldUseTranslatableSearch($column, $operator, $value): bool
    {
        // Only for queries with an actual operator
        if (!is_string($operator)) {
            return false;
        }

        // Only for LIKE operations (case-insensitive check)
        if (strtoupper($operator) !== 'LIKE') {
            return false;
        }

        // Only for string values
        if (!is_string($value)) {
            return false;
        }

        // Only for models with translatable attributes
        $model = $this->getModel();
        if (!method_exists($model, 'isTranslatableAttribute')) {
            return false;
        }

        // Only for columns that are actually translatable
        // Strip table prefix if present (e.g., "cities.name" -> "name")
        $columnName = str_contains($column, '.') ? substr($column, strrpos($column, '.') + 1) : $column;
        return $model->isTranslatableAttribute($columnName);
    }

    /**
     * Apply case-insensitive search across all locales for a translatable JSON column
     */
    protected function whereTranslatableJson($column, $value, $boolean = 'and')
    {
        // Use the column as-is if it already has table prefix, otherwise add it
        $fullColumnName = str_contains($column, '.') ? $column : $this->getModel()->getTable() . '.' . $column;

        return $this->where(function ($query) use ($fullColumnName, $value) {
            $locales = array_keys(LocalizationService::getLaravelLocalizationLocales());

            foreach ($locales as $locale) {
                $query->orWhereRaw(
                    "LOWER(JSON_UNQUOTE(JSON_EXTRACT({$fullColumnName}, ?))) LIKE LOWER(?)",
                    ['$."' . $locale . '"', $value]
                );
            }
        }, null, null, $boolean);
    }
}
