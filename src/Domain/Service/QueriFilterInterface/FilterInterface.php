<?php

namespace App\Domain\Service\QueriFilterInterface;

interface FilterInterface
{
    /**
     * @return $this
     */
    public function initFilter(object $criteriasDto, string $tableName, string $alias = 'u', string $select = '*'): self;

    /**
     * @return mixed[]
     */
    public function getList(): array;

    /**
     * @return mixed[]|null
     */
    public function getOne(): ?array;

    public function getSql(): string;

    public function getCount(): int;

    /**
     * @return mixed[]
     */
    public function getParameter(): array;
}
