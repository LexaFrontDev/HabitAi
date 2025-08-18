<?php

namespace App\Domain\Repository\Matric;

use App\Aplication\Dto\Matric\RenameColumnMatric;

interface MatricColumnInterface
{
    public function renameMatricColumn(RenameColumnMatric $dto): bool;
}
