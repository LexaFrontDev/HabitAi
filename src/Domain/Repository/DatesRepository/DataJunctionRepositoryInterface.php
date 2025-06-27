<?php

namespace App\Domain\Repository\DatesRepository;

use App\Aplication\Dto\DatesDto\ReqDataJunction;

interface DataJunctionRepositoryInterface
{


    public function saveDateJunction(ReqDataJunction $data): int|bool;

}