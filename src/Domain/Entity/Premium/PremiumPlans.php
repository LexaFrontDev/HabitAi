<?php

namespace App\Domain\Entity\Premium;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'premium_plans')]
class PremiumPlans
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;


    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'description', type: 'string', nullable: false)]
    private string $description;


    #[ORM\Column(name: 'price', type: 'string', nullable: false)]
    private string $price;

    /**
     * @var string[] $features
     */
    #[ORM\Column(name: 'features', type: 'json', nullable: false)]
    private array $features;

    #[ORM\Column(name: 'highlight', type: 'boolean', nullable: false)]
    private bool $highlight;



    // /getters setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesc(): string
    {
        return $this->description;
    }

    public function setDesc(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string[]
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @param string[] $features
     */
    public function setFeatures(array $features): void
    {
        $this->features = $features;
    }

    public function getHighlight(): bool
    {
        return $this->highlight;
    }

    public function setHighlight(bool $highlight): void
    {
        $this->highlight = $highlight;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): string
    {
        return $this->price;
    }
}
