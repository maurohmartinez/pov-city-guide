<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum VisibilityEnum: string
{
    use EnumTrait;

    case PUBLIC = 'PUBLIC';
    case PRIVATE = 'PRIVATE';
    case HIDDEN = 'HIDDEN';
}
