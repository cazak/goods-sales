<?php

declare(strict_types=1);

namespace App\Model\Buyer\Entity\Exception;

use RuntimeException;
use Throwable;

final class InsufficientFundsException extends RuntimeException
{
    public function __construct(string $message = 'Insufficient funds', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
