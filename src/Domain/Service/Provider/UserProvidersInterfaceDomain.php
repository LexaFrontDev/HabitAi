<?php

namespace App\Domain\Service\Provider;

use App\Domain\Entity\Users;

interface UserProvidersInterfaceDomain
{
    public function loadUserByIdentifier(string $identifier): Users;

    public function refreshUser(Users $user): Users;

    public function supportsClass(string $class): bool;
}
