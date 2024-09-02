<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Query\GetStatus;

final readonly class PurchaseStatus
{
    public function __construct(
        public string $unique_code,
        public int $price,
    ) {}
}
