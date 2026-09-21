<?php

namespace App\Traits;

use App\Services\ImageProcessingService;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasImages
{
    protected function smallImage(): Attribute
    {
        return Attribute::make(
            get: fn () => ImageProcessingService::getSmallPath($this),
        );
    }
}
