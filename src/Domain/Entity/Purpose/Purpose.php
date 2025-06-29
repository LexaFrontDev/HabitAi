<?php

namespace App\Domain\Entity\Purpose;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "purposes")]
class Purpose
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private int $habitsId;

    #[ORM\Column(type: "string")]
    private string $type;

    #[ORM\Column(type: "integer")]
    private int $count;

    #[ORM\Column(type: "boolean")]
    private bool $checkManually = false;


    #[ORM\Column(type: "integer")]
    private int $autoCount = 0;

    #[ORM\Column(type: "boolean")]
    private bool $checkAuto = false;

    #[ORM\Column(type: "boolean")]
    private bool $checkClose = false;

#[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $created_at = null;

   #[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_delete = false;

    // ======= Getters & Setters =======
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    public function isCheckManually(): bool
    {
        return $this->checkManually;
    }

    public function setCheckManually(bool $checkManually): self
    {
        $this->checkManually = $checkManually;
        return $this;
    }

    public function isCheckAuto(): bool
    {
        return $this->checkAuto;
    }

    public function setCheckAuto(bool $checkAuto): self
    {
        $this->checkAuto = $checkAuto;
        return $this;
    }

    public function isCheckClose(): bool
    {
        return $this->checkClose;
    }

    public function setCheckClose(bool $checkClose): self
    {
        $this->checkClose = $checkClose;
        return $this;
    }

    /**
     * @param int $habitsId
     */
    public function setHabitsId(int $habitsId): void
    {
        $this->habitsId = $habitsId;
    }


    /**
     * @return int
     */
    public function getHabitsId(): int
    {
        return $this->habitsId;
    }


    /**
     * @param int $autoCount
     */
    public function setautoCount(int $autoCount): void
    {
        $this->autoCount = $autoCount;
    }


    /**
     * @return int
     */
    public function getautoCount(): int
    {
        return $this->autoCount;
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
