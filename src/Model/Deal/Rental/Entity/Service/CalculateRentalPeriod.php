<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Entity\Service;

use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use DateInterval;
use DateTimeImmutable;

final readonly class CalculateRentalPeriod
{
    /**
     * @return array<DateTimeImmutable>
     */
    public static function calculate(Duration $duration): array
    {
        return match ($duration) {
            Duration::FourHours => self::getFourHoursPeriod(),
            Duration::EightHours => self::getEightHoursPeriod(),
            Duration::TwelveHours => self::getTwelveHoursPeriod(),
            Duration::TwentyFourHours => self::getTwentyFourHoursPeriod(),
        };
    }

    public static function updateEndDate(DateTimeImmutable $rentalStartDate, Duration $duration): DateTimeImmutable
    {
        $startDate = $rentalStartDate;

        return match ($duration) {
            Duration::FourHours => $startDate->add(new DateInterval('PT4H')),
            Duration::EightHours => $startDate->add(new DateInterval('PT8H')),
            Duration::TwelveHours => $startDate->add(new DateInterval('PT12H')),
            Duration::TwentyFourHours => $startDate->add(new DateInterval('PT24H')),
        };
    }

    /**
     * @return array<DateTimeImmutable>
     */
    private static function getFourHoursPeriod(): array
    {
        $startDate = new DateTimeImmutable();
        $endDate = $startDate->add(new DateInterval('PT4H'));

        return [$startDate, $endDate];
    }

    /**
     * @return array<DateTimeImmutable>
     */
    private static function getEightHoursPeriod(): array
    {
        $startDate = new DateTimeImmutable();
        $endDate = $startDate->add(new DateInterval('PT8H'));

        return [$startDate, $endDate];
    }

    /**
     * @return array<DateTimeImmutable>
     */
    private static function getTwelveHoursPeriod(): array
    {
        $startDate = new DateTimeImmutable();
        $endDate = $startDate->add(new DateInterval('PT12H'));

        return [$startDate, $endDate];
    }

    /**
     * @return array<DateTimeImmutable>
     */
    private static function getTwentyFourHoursPeriod(): array
    {
        $startDate = new DateTimeImmutable();
        $endDate = $startDate->add(new DateInterval('PT24H'));

        return [$startDate, $endDate];
    }
}
