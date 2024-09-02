<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Query\GetStatus;

final readonly class RentalStatus
{
    public function __construct(
        public string $unique_code,
        public int $price,
        public string $duration,
        public string $start_date,
        public string $end_date,
    ) {}
}
