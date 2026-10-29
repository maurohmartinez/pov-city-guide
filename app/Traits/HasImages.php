<?php

namespace App\Traits;

use App\Services\ImageProcessingService;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasImages
{
    protected function smallImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? ImageProcessingService::getSmallPath($this) : null,
        );
    }

    protected function mediumImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? ImageProcessingService::getMediumPath($this) : null,
        );
    }

    protected function largeImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? ImageProcessingService::getLargePath($this) : null,
        );
    }

    protected function originalImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? ImageProcessingService::getOriginalPath($this) : null,
        );
    }
}
