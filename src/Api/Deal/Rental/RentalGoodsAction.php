<?php

declare(strict_types=1);

namespace App\Api\Deal\Rental;

use App\Api\AbstractApiController;
use App\Api\ParameterBag;
use App\Model\Deal\Rental\Command\RentalGoods\RentalGoodsCommand;
use App\Model\Deal\Rental\Command\RentalGoods\RentalGoodsCommandHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/rental/goods', methods: ['POST'], name: 'rental_goods')]
final class RentalGoodsAction extends AbstractApiController
{
    public function __construct(
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private RentalGoodsCommandHandler $rentalGoodsCommandHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $payload = ParameterBag::createFromJson($request->getContent());

        $command = new RentalGoodsCommand(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $payload->getString('goods_id'),
            $payload->getString('duration'),
        );

        $violations = $this->validator->validate($command);
        if (\count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        ($this->rentalGoodsCommandHandler)($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
