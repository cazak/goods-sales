<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Entity\Service;

use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use App\Model\Goods\Entity\Goods;

final readonly class CalculateRentalPrice
{
    public static function calculate(Goods $goods, Duration $duration): int
    {
        return match ($duration) {
            Duration::FourHours => $goods->getPrice()->getFourHours(),
            Duration::EightHours => $goods->getPrice()->getEightHours(),
            Duration::TwelveHours => $goods->getPrice()->getTwelveHours(),
            Duration::TwentyFourHours => $goods->getPrice()->getTwentyFourHours(),
        };
    }
}
