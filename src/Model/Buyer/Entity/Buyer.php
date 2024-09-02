<?php

declare(strict_types=1);

namespace App\Model\Buyer\Entity;

use App\Model\Buyer\Entity\Exception\InsufficientFundsException;
use App\Model\Buyer\Entity\ValueObject\Name;
use App\Model\User\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/** @final */
#[ORM\Entity]
#[ORM\Table(name: '`buyer`')]
class Buyer
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\OneToOne(targetEntity: User::class, mappedBy: 'buyer')]
    private ?User $user;

    #[ORM\Embedded]
    private Name $name;

    #[ORM\Column]
    private int $moneyAmount;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, Name $name, int $moneyAmount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->moneyAmount = $moneyAmount;
        $this->user = null;
        $this->createdAt = new DateTimeImmutable();
    }

    public function pay(int $money): void
    {
        if ($money > $this->moneyAmount) {
            throw new InsufficientFundsException();
        }

        $this->moneyAmount -= $money;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getMoneyAmount(): int
    {
        return $this->moneyAmount;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
