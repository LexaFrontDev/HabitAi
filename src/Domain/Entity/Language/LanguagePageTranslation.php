<?php

namespace App\Domain\Entity\Language;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class LanguagePageTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $pageName;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: 'json')]
    private array $pageTranslate = [];

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Language $language;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_delete = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageName(): string
    {
        return $this->pageName;
    }

    public function setPageName(string $pageName): static
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getPageTranslate(): array
    {
        return $this->pageTranslate;
    }

    /**
     * @param array<string, string> $pageTranslate
     *
     * @return $this
     */
    public function setPageTranslate(array $pageTranslate): static
    {
        $this->pageTranslate = $pageTranslate;

        return $this;
    }

    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getis_delete(): bool
    {
        return $this->is_delete;
    }

    public function setis_delete(bool $is_delete): self
    {
        $this->is_delete = $is_delete;

        return $this;
    }
}
