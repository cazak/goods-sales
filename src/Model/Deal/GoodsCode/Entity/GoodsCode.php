<?php

declare(strict_types=1);

namespace App\Model\Deal\GoodsCode\Entity;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Deal\GoodsCode\Entity\ValueObject\DealType;
use App\Model\Goods\Entity\Goods;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/** @final */
#[ORM\Entity]
#[ORM\Table(name: '`goods_code`')]
class GoodsCode
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $dealId;

    #[ORM\Column]
    private DealType $dealType;

    #[ORM\ManyToOne(targetEntity: Goods::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Goods $goods;

    #[ORM\ManyToOne(targetEntity: Buyer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Buyer $buyer;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(Uuid $id, Uuid $dealId, DealType $dealType, Goods $goods, Buyer $buyer)
    {
        $this->id = $id;
        $this->dealId = $dealId;
        $this->dealType = $dealType;
        $this->goods = $goods;
        $this->buyer = $buyer;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getDealId(): Uuid
    {
        return $this->dealId;
    }

    public function getDealType(): DealType
    {
        return $this->dealType;
    }

    public function getGoods(): Goods
    {
        return $this->goods;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
