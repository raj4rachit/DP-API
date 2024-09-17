<?php

declare(strict_types=1);

namespace Modules\V1\User\Enums;

use Shared\Helpers\StringHelper;

enum RoleEnum: int
{
    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case USER = 3;
    case PATIENT = 4;
    case DOCTOR = 5;
    case LAB_ASSISTANT = 6;

    public static function names(): array
    {
        return array_map(fn (RoleEnum $roles) => StringHelper::toTitleCase($roles->name), self::cases());
    }
}
