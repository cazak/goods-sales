<?php

declare(strict_types=1);

namespace App\Api;

use App\Model\Buyer\Entity\Buyer;
use App\Security\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    final protected function getAuthorizedBuyer(): Buyer
    {
        $user = $this->getUser();
        \assert($user instanceof SecurityUser);

        return $user->getUser()->getBuyer();
    }
}
