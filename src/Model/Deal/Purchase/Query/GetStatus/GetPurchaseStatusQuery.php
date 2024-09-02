<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Query\GetStatus;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetPurchaseStatusQuery
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $purchaseId,
    ) {}
}
