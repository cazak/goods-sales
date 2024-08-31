<?php

declare(strict_types=1);

namespace App\User\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
final readonly class Email
{
    public function __construct(
        #[ORM\Column]
        private string $value,
    ) {
        Assert::email($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equalTo(self $other): bool
    {
        return $this->value === $other->value;
    }
}
