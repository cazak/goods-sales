<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Access\CheckBuyerAccess;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CheckBuyerAccess
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $buyerId,
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $rentalId,
    ) {}
}
