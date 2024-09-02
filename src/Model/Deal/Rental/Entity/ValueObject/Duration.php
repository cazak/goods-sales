<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Entity\ValueObject;

enum Duration: string
{
    case FourHours = 'four_hours';
    case EightHours = 'eight_hours';
    case TwelveHours = 'twelve_hours';
    case TwentyFourHours = 'twenty_four_hours';

    /**
     * @return array<string>
     */
    public static function casesAtString(): array
    {
        return array_map(static fn (self $duration): string => $duration->value, self::cases());
    }

    public function isExtending(self $duration): bool
    {
        return $this->getNumericValue() < $duration->getNumericValue();
    }

    private function getNumericValue(): int
    {
        return match ($this) {
            self::FourHours => 4,
            self::EightHours => 8,
            self::TwelveHours => 12,
            self::TwentyFourHours => 24,
        };
    }
}
