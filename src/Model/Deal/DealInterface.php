<?php

declare(strict_types=1);

namespace App\Model\Deal;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Goods\Entity\Goods;
use Symfony\Component\Uid\Uuid;

interface DealInterface
{
    public function getId(): Uuid;

    public function getGoods(): Goods;

    public function getBuyer(): Buyer;
}
