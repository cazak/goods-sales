<?php

declare(strict_types=1);

namespace App\Security;

interface PasswordHasherInterface
{
    public function hash(string $password): string;
}
