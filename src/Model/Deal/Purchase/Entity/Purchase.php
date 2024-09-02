<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Entity;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Deal\DealInterface;
use App\Model\Goods\Entity\Goods;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/** @final */
#[ORM\Entity]
#[ORM\Table(name: '`purchase`')]
class Purchase implements DealInterface
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $id;

    #[ORM\ManyToOne(targetEntity: Goods::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Goods $goods;

    #[ORM\ManyToOne(targetEntity: Buyer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Buyer $buyer;

    #[ORM\Column]
    private int $price;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, Goods $goods, Buyer $buyer)
    {
        $this->id = $id;
        $this->goods = $goods;
        $this->buyer = $buyer;
        $this->price = $goods->getPrice()->getPurchase();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getGoods(): Goods
    {
        return $this->goods;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
