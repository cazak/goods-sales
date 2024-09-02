<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Command\ExtendRental;

use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ExtendRentalCommand
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $buyerId,
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $rentalId,
        #[Assert\Choice(callback: [Duration::class, 'casesAtString'], message: 'The duration type is not valid.')]
        #[Assert\NotBlank]
        public string $duration,
    ) {}
}
