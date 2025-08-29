<?php

namespace App\Domain\Entity\Premium;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'benefits')]
class Benefits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'title', type: 'string', length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'string', nullable: false)]
    private string $description;

    #[ORM\Column(name: 'icon_path', type: 'string', nullable: false)]
    private string $icon_path;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDesc(): string
    {
        return $this->description;
    }

    public function setDesc(string $description): void
    {
        $this->description = $description;
    }

    public function getIconPath(): string
    {
        return $this->icon_path;
    }

    public function setIconPath(string $icon_path): void
    {
        $this->icon_path = $icon_path;
    }
}
