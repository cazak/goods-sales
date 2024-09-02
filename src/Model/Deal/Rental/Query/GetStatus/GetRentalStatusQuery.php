<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Query\GetStatus;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRentalStatusQuery
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $rentalId,
    ) {}
}
