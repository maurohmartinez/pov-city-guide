<?php

namespace App\Enums;

use App\Traits\Enum;

enum CategoryTemplateEnum: string
{
    use Enum;

    case SIMPLE = 'simple';
    case WITH_SUBCATEGORIES = 'with_subcategories';
    case WITH_VIDEOS = 'with_videos';
}
