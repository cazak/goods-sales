<?php

declare(strict_types=1);

namespace App\Api\Deal\Rental;

use App\Api\AbstractApiController;
use App\Model\Deal\Rental\Query\GetHistory\GetRentalHistoryQuery;
use App\Model\Deal\Rental\Query\GetHistory\GetRentalHistoryQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/rental/history', methods: ['GET'], name: 'rental_history')]
final class RentalHistoryAction extends AbstractApiController
{
    public function __construct(
        private readonly GetRentalHistoryQueryHandler $rentalHistoryQueryHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $query = new GetRentalHistoryQuery(
            $this->getAuthorizedBuyer()->getId()->toString(),
        );

        $purchases = ($this->rentalHistoryQueryHandler)($query);

        return new JsonResponse($purchases, Response::HTTP_OK);
    }
}
