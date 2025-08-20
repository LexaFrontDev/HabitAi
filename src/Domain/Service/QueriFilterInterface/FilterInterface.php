<?php

namespace App\Domain\Service\QueriFilterInterface;

use Doctrine\DBAL\Exception;

interface FilterInterface
{
    /**
     * @return $this
     */
    public function initFilter(object $criteriasDto, string $tableName, string $alias = 'u', string $select = '*'): self;

    /**
     * @return mixed[]
     * @throws Exception
     */
    public function getList(): array;

    /**
     * @return mixed[]|null
     *
     * @throws Exception
     */
    public function getOne(): ?array;

    public function getSql(): string;

    /**
     * @return mixed[]
     */
    public function getParameter(): array;
}
