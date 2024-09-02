<?php

declare(strict_types=1);

namespace App\Model\Buyer\Command\Create;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Buyer\Entity\ValueObject\Name;

final readonly class CreateBuyerCommandHandler
{
    public function __construct(private BuyerRepository $repository) {}

    public function __invoke(CreateBuyerCommand $command): void
    {
        $buyer = new Buyer(
            $command->id,
            new Name(
                $command->name,
                $command->surname,
            ),
            $command->moneyAmount,
        );

        $this->repository->save($buyer);
    }
}
