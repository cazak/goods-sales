<?php

declare(strict_types=1);

namespace App\Model\Goods\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
final readonly class Price
{
    #[ORM\Column(type: 'integer')]
    private int $purchase;

    #[ORM\Column(type: 'integer')]
    private int $fourHours;

    #[ORM\Column(type: 'integer')]
    private int $eightHours;

    #[ORM\Column(type: 'integer')]
    private int $twelveHours;

    #[ORM\Column(type: 'integer')]
    private int $twentyFourHours;

    public function __construct(
        int $purchase,
        int $fourHours,
        int $eightHours,
        int $twelveHours,
        int $twentyFourHours,
    ) {
        Assert::notEmpty($purchase);
        Assert::notEmpty($fourHours);
        Assert::notEmpty($eightHours);
        Assert::notEmpty($twelveHours);
        Assert::notEmpty($twentyFourHours);

        $this->purchase = $purchase;
        $this->fourHours = $fourHours;
        $this->eightHours = $eightHours;
        $this->twelveHours = $twelveHours;
        $this->twentyFourHours = $twentyFourHours;
    }

    public function getPurchase(): int
    {
        return $this->purchase;
    }

    public function getFourHours(): int
    {
        return $this->fourHours;
    }

    public function getEightHours(): int
    {
        return $this->eightHours;
    }

    public function getTwelveHours(): int
    {
        return $this->twelveHours;
    }

    public function getTwentyFourHours(): int
    {
        return $this->twentyFourHours;
    }
}
