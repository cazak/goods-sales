<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Entity;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Deal\DealInterface;
use App\Model\Deal\Rental\Entity\Service\CalculateRentalPeriod;
use App\Model\Deal\Rental\Entity\Service\CalculateRentalPrice;
use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use App\Model\Goods\Entity\Goods;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Symfony\Component\Uid\Uuid;

/** @final */
#[ORM\Entity]
#[ORM\Table(name: '`rental`')]
class Rental implements DealInterface
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
    private Duration $duration;

    #[ORM\Column]
    private int $price;

    #[ORM\Column]
    private readonly DateTimeImmutable $startDate;

    #[ORM\Column]
    private DateTimeImmutable $endDate;

    public function __construct(Uuid $id, Goods $goods, Buyer $buyer, Duration $duration)
    {
        $this->id = $id;
        $this->goods = $goods;
        $this->buyer = $buyer;
        $this->duration = $duration;
        $this->price = CalculateRentalPrice::calculate($goods, $duration);
        [$this->startDate, $this->endDate] = CalculateRentalPeriod::calculate($duration);
    }

    public function extend(Duration $duration): void
    {
        $currentDate = new DateTimeImmutable();
        if ($currentDate >= $this->endDate) {
            throw new DomainException('The rental has ended.');
        }

        if (!$this->duration->isExtending($duration)) {
            throw new DomainException('The new rental duration must be longer than the current one.');
        }

        $this->duration = $duration;
        $this->price = CalculateRentalPrice::calculate($this->goods, $duration);
        $this->endDate = CalculateRentalPeriod::updateEndDate($this->startDate, $duration);
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

    public function getDuration(): Duration
    {
        return $this->duration;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }
}
