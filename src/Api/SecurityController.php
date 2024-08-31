<?php

declare(strict_types=1);

namespace App\Api;

use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final readonly class SecurityController
{
    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    public function loginCheck(): JsonResponse
    {
        throw new RuntimeException('The action must not be called directly.');
    }
}
