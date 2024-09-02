<?php

declare(strict_types=1);

namespace App\Model\User\Entity\ValueObject;

enum Role: string
{
    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';

    /**
     * @return array<int, string>
     */
    public static function casesAtString(): array
    {
        return array_map(static fn (self $role): string => $role->value, self::cases());
    }
}
