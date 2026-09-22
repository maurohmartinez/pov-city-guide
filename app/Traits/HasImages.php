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

    protected function mediumImage(): Attribute
    {
        return Attribute::make(
            get: fn () => ImageProcessingService::getMediumPath($this),
        );
    }

    protected function largeImage(): Attribute
    {
        return Attribute::make(
            get: fn () => ImageProcessingService::getLargePath($this),
        );
    }

    protected function originalImage(): Attribute
    {
        return Attribute::make(
            get: fn () => ImageProcessingService::getOriginalPath($this),
        );
    }
}
