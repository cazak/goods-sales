<?php

declare(strict_types=1);

namespace App\Api\Deal\Rental;

use App\Api\AbstractApiController;
use App\Api\ParameterBag;
use App\Model\Deal\Rental\Access\CheckBuyerAccess\CheckBuyerAccess;
use App\Model\Deal\Rental\Access\CheckBuyerAccess\CheckBuyerAccessHandler;
use App\Model\Deal\Rental\Command\ExtendRental\ExtendRentalCommand;
use App\Model\Deal\Rental\Command\ExtendRental\ExtendRentalCommandHandler;
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
#[Route(path: '/rental/extend/{rental_id}', methods: ['PUT'], name: 'extend_rental_goods')]
final class ExtendRentalAction extends AbstractApiController
{
    public function __construct(
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private ExtendRentalCommandHandler $extendRentalCommandHandler,
        private CheckBuyerAccessHandler $checkBuyerAccessHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var ParameterBag<Payload> $attributes */
        $attributes = new ParameterBag($request->attributes->all());
        $payload = new ParameterBag($request->getPayload()->all());

        $this->checkBuyerAccess($attributes);

        $command = new ExtendRentalCommand(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $attributes->getString('rental_id'),
            $payload->getString('duration'),
        );

        $violations = $this->validator->validate($command);
        if (\count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        ($this->extendRentalCommandHandler)($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param ParameterBag<Payload> $attributes
     */
    private function checkBuyerAccess(ParameterBag $attributes): void
    {
        $checkBuyerAccess = new CheckBuyerAccess(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $attributes->getString('rental_id'),
        );

        $hasAccess = ($this->checkBuyerAccessHandler)($checkBuyerAccess);

        if (!$hasAccess) {
            throw $this->createAccessDeniedException();
        }
    }
}
