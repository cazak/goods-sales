<?php

declare(strict_types=1);

namespace App\Buyer\Command\Create;

use Symfony\Component\Uid\Uuid;

final readonly class CreateBuyerCommand
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public string $surname,
    ) {}
}
