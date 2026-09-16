<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait UseTranslatableToArray
{
    public function toTranslatableArray(): array
    {
        $attributes = parent::toArray();

        foreach ($this->translatable as $value) {
            $attributes[$value] = $this->getTranslation($value, App::getLocale());
        }

        return $attributes;
    }
}
