<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Buyer\Entity\Buyer;
use App\User\Entity\ValueObject\Email;
use App\User\Entity\ValueObject\Role;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/** @final */
#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', columns: ['email_value'])]
class User
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $id;

    #[ORM\OneToOne(targetEntity: Buyer::class)]
    #[ORM\JoinColumn(name: 'buyer_id', referencedColumnName: 'id', onDelete: 'CASCADE', nullable: false)]
    private Buyer $buyer;

    #[ORM\Embedded]
    private Email $email;

    #[ORM\Column]
    private Role $role;

    #[ORM\Column]
    private string $password;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, Buyer $buyer, Email $email, Role $role, string $password)
    {
        $this->id = $id;
        $this->buyer = $buyer;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
