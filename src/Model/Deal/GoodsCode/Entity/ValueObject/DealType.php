<?php

declare(strict_types=1);

namespace App\Model\Deal\GoodsCode\Entity\ValueObject;

enum DealType: string
{
    case Purchase = 'Purchase';
    case Rental = 'Rental';

    /**
     * @return array<string>
     */
    public static function casesAtString(): array
    {
        return array_map(static fn (self $dealType): string => $dealType->value, self::cases());
    }
}
