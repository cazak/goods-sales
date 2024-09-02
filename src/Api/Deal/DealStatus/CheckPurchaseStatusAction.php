<?php

declare(strict_types=1);

namespace App\Api\Deal\DealStatus;

use App\Api\AbstractApiController;
use App\Api\ParameterBag;
use App\Model\Deal\GoodsCode\Command\Generate\GenerateGoodsCodeForBuyerCommand;
use App\Model\Deal\GoodsCode\Command\Generate\GenerateGoodsCodeForBuyerCommandHandler;
use App\Model\Deal\GoodsCode\Entity\ValueObject\DealType;
use App\Model\Deal\Purchase\Access\CheckBuyerAccess\CheckBuyerAccess;
use App\Model\Deal\Purchase\Access\CheckBuyerAccess\CheckBuyerAccessHandler;
use App\Model\Deal\Purchase\Query\GetStatus\GetPurchaseStatusQuery;
use App\Model\Deal\Purchase\Query\GetStatus\GetPurchaseStatusQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @phpstan-type Payload = array{
 *     pruchase_id: string
 * }
 */
#[Route(path: '/purchase/status', methods: ['POST'], name: 'purchase_status')]
final class CheckPurchaseStatusAction extends AbstractApiController
{
    public function __construct(
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private GenerateGoodsCodeForBuyerCommandHandler $goodsCodeForBuyerCommandHandler,
        private CheckBuyerAccessHandler $checkBuyerAccessHandler,
        private GetPurchaseStatusQueryHandler $getPuchaseStatusQueryHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var ParameterBag<Payload> $payload */
        $payload = ParameterBag::createFromJson($request->getContent());

        $this->checkBuyerAccess($payload);
        $this->generateUniqueCodeIfNeeded($payload);

        $query = new GetPurchaseStatusQuery(
            $payload->getString('purchase_id'),
        );

        $this->validator->validate($query);

        $violations = $this->validator->validate($query);
        if (\count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        $status = ($this->getPuchaseStatusQueryHandler)($query);

        return new JsonResponse($status, Response::HTTP_OK);
    }

    /**
     * @param ParameterBag<Payload> $payload
     */
    private function generateUniqueCodeIfNeeded(ParameterBag $payload): void
    {
        $command = new GenerateGoodsCodeForBuyerCommand(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $payload->getString('purchase_id'),
            DealType::Purchase->value,
        );

        ($this->goodsCodeForBuyerCommandHandler)($command);
    }

    /**
     * @param ParameterBag<Payload> $payload
     */
    private function checkBuyerAccess(ParameterBag $payload): void
    {
        $checkBuyerAccess = new CheckBuyerAccess(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $payload->getString('purchase_id'),
        );

        $hasAccess = ($this->checkBuyerAccessHandler)($checkBuyerAccess);

        if (!$hasAccess) {
            throw $this->createAccessDeniedException();
        }
    }
}
