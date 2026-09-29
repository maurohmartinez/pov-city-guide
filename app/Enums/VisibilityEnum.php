<?php

namespace App\Enums;

use App\Traits\Enum;

enum VisibilityEnum: string
{
    use Enum;

    case PUBLIC = 'PUBLIC';
    case PRIVATE = 'PRIVATE';
    case HIDDEN = 'HIDDEN';
}
