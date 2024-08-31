<?php

declare(strict_types=1);

namespace App\Buyer\Entity;

use App\Buyer\Entity\ValueObject\Name;
use App\User\Entity\User;
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
    private readonly DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, Name $name)
    {
        $this->id = $id;
        $this->user = null;
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
