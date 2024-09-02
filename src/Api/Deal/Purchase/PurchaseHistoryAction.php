<?php

declare(strict_types=1);

namespace App\Api\Deal\Purchase;

use App\Api\AbstractApiController;
use App\Model\Deal\Purchase\Query\GetHistory\GetPurchaseHistoryQuery;
use App\Model\Deal\Purchase\Query\GetHistory\GetPurchaseHistoryQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/purchase/history', methods: ['GET'], name: 'purchase_history')]
final class PurchaseHistoryAction extends AbstractApiController
{
    public function __construct(
        private readonly GetPurchaseHistoryQueryHandler $purchaseHistoryQueryHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $query = new GetPurchaseHistoryQuery(
            $this->getAuthorizedBuyer()->getId()->toString(),
        );

        $purchases = ($this->purchaseHistoryQueryHandler)($query);

        return new JsonResponse($purchases, Response::HTTP_OK);
    }
}
