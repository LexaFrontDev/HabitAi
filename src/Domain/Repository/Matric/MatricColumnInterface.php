<?php

namespace App\Domain\Repository\Matric;

use App\Aplication\Dto\Matric\RenameColumnMatric;

interface MatricColumnInterface
{
    /**
     * @param RenameColumnMatric $dto
     * @return bool
     */
    public function renameMatricColumn(RenameColumnMatric $dto): bool;
}