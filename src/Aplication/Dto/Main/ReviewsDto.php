<?php

namespace App\Aplication\Dto\Main;

class ReviewsDto
{
    public function __construct(
        private string $name,
        private string $comment,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
