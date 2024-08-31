<?php

declare(strict_types=1);

namespace App\Buyer\Command\Create;

use App\Buyer\Entity\Buyer;
use App\Buyer\Entity\BuyerRepository;
use App\Buyer\Entity\ValueObject\Name;

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
        );

        $this->repository->save($buyer);
    }
}
