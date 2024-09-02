<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Query\GetHistory;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRentalHistoryQuery
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $buyerId,
    ) {}
}
