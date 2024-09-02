<?php

declare(strict_types=1);

namespace App\Api\Deal\DealStatus;

use App\Api\AbstractApiController;
use App\Api\ParameterBag;
use App\Model\Deal\GoodsCode\Command\Generate\GenerateGoodsCodeForBuyerCommand;
use App\Model\Deal\GoodsCode\Command\Generate\GenerateGoodsCodeForBuyerCommandHandler;
use App\Model\Deal\GoodsCode\Entity\ValueObject\DealType;
use App\Model\Deal\Rental\Access\CheckBuyerAccess\CheckBuyerAccess;
use App\Model\Deal\Rental\Access\CheckBuyerAccess\CheckBuyerAccessHandler;
use App\Model\Deal\Rental\Query\GetStatus\GetRentalStatusQuery;
use App\Model\Deal\Rental\Query\GetStatus\GetRentalStatusQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @phpstan-type Payload = array{
 *     rental_id: string
 * }
 */
#[Route(path: '/rental/status', methods: ['POST'], name: 'rental_status')]
final class CheckRentalStatusAction extends AbstractApiController
{
    public function __construct(
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private GenerateGoodsCodeForBuyerCommandHandler $goodsCodeForBuyerCommandHandler,
        private CheckBuyerAccessHandler $checkBuyerAccessHandler,
        private GetRentalStatusQueryHandler $getRentalStatusQueryHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var ParameterBag<Payload> $payload */
        $payload = ParameterBag::createFromJson($request->getContent());

        $this->checkBuyerAccess($payload);
        $this->generateUniqueCodeIfNeeded($payload);

        $query = new GetRentalStatusQuery(
            $payload->getString('rental_id'),
        );

        $this->validator->validate($query);

        $violations = $this->validator->validate($query);
        if (\count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        $status = ($this->getRentalStatusQueryHandler)($query);

        return new JsonResponse($status, Response::HTTP_OK);
    }

    /**
     * @param ParameterBag<Payload> $payload
     */
    private function generateUniqueCodeIfNeeded(ParameterBag $payload): void
    {
        $command = new GenerateGoodsCodeForBuyerCommand(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $payload->getString('rental_id'),
            DealType::Rental->value,
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
            $payload->getString('rental_id'),
        );

        $hasAccess = ($this->checkBuyerAccessHandler)($checkBuyerAccess);

        if (!$hasAccess) {
            throw $this->createAccessDeniedException();
        }
    }
}
