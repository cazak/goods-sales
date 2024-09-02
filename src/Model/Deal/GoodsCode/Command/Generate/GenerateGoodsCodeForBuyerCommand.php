<?php

declare(strict_types=1);

namespace App\Model\Deal\GoodsCode\Command\Generate;

use App\Model\Deal\GoodsCode\Entity\ValueObject\DealType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GenerateGoodsCodeForBuyerCommand
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $buyerId,
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $dealId,
        #[Assert\Choice(callback: [DealType::class, 'casesAtString'], message: 'The deal type is not valid.')]
        #[Assert\NotBlank]
        public string $dealType,
    ) {}
}
