<?php

declare(strict_types=1);

namespace App\Model\Goods\Entity;

use App\Model\Goods\Entity\ValueObject\Price;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/** @final */
#[ORM\Entity]
#[ORM\Table(name: '`goods`')]
class Goods
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Embedded]
    private Price $price;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    public function __construct(Uuid $id, Price $price, string $name)
    {
        $date = new DateTimeImmutable();

        $this->id = $id;
        $this->price = $price;
        $this->name = $name;
        $this->createdAt = $date;
        $this->updatedAt = clone $date;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
