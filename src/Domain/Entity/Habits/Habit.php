<?php

namespace App\Domain\Entity\Habits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "Habits")]
class Habit
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;


    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $userId = null;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(nullable: true)]
    private ?string $iconUrl = null;

    #[ORM\Column(nullable: true)]
    private ?string $quote = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $goalInDays = null;

    #[ORM\Column(type: "string")]
    private ?string $dataType = 'Daily';

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $beginDate = null;

    #[ORM\Column(type: "string")]
    private ?string $notificationDate = null;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата создания записи'])]
    private ?DateTimeInterface $created_at = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;


    // ======= Getters & Setters =======

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getIconUrl(): ?string
    {
        return $this->iconUrl;
    }

    public function setIconUrl(?string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(?string $quote): self
    {
        $this->quote = $quote;
        return $this;
    }



    public function getGoalInDays(): ?int
    {
        return $this->goalInDays;
    }

    public function setGoalInDays(?int $goalInDays): self
    {
        $this->goalInDays = $goalInDays;
        return $this;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(?\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;
        return $this;
    }




    public function getDateType(): string
    {
        return $this->dataType;
    }
    public function setDateType(string $dataType): self
    {
        $this->dataType = $dataType;
        return $this;
    }



   public function getNotificationDate(): ?string
   {
       return $this->notificationDate;
   }

   public function setNotificationDate(?string $notificationDate): self
   {
       $this->notificationDate = $notificationDate;
       return $this;
   }


    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
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