<?php

declare(strict_types=1);

namespace App\Api\Deal\Purchase;

use App\Api\AbstractApiController;
use App\Api\ParameterBag;
use App\Model\Deal\Purchase\Command\PurchaseGoods\PurchaseGoodsCommand;
use App\Model\Deal\Purchase\Command\PurchaseGoods\PurchaseGoodsCommandHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/purchase/goods', methods: ['POST'], name: 'purchase_goods')]
final class PurchaseGoodsAction extends AbstractApiController
{
    public function __construct(
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private PurchaseGoodsCommandHandler $purchaseGoodsCommandHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $payload = ParameterBag::createFromJson($request->getContent());

        $command = new PurchaseGoodsCommand(
            $this->getAuthorizedBuyer()->getId()->toString(),
            $payload->getString('goods_id'),
        );

        $violations = $this->validator->validate($command);
        if (\count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');

            return new JsonResponse($json, Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        ($this->purchaseGoodsCommandHandler)($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
