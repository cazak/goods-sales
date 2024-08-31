<?php

declare(strict_types=1);

namespace App\User\Command\Create;

use App\Buyer\Entity\Buyer;

final readonly class CreateUserCommand
{
    public function __construct(
        public Buyer $buyer,
        public string $email,
        public string $plainPassword,
        public string $role,
    ) {}
}
