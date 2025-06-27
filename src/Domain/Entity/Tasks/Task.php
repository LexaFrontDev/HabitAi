<?php

namespace App\Domain\Entity\Tasks;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tasks")]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;


    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $purposeId = null;

    #[ORM\Column(type: "string")]
    private string $taskType;

    #[ORM\Column(type: "string")]
    private string $titleTask;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $notificationId = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $beginDate = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dueDate = null;




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


    public function getTaskType(): string
    {
        return $this->taskType;
    }

    public function setTaskType(string $taskType): self
    {
        $this->taskType = $taskType;
        return $this;
    }

    public function getTitleTask(): string
    {
        return $this->titleTask;
    }

    public function setTitleTask(string $titleTask): self
    {
        $this->titleTask = $titleTask;
        return $this;
    }





    public function getNotificationId(): ?int
    {
        return $this->notificationId;
    }

    public function setNotificationId(?int $notificationId): self
    {
        $this->notificationId = $notificationId;
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

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }


    public function getPurposeId(): ?int
    {
        return $this->purposeId;
    }

    public function setPurposeId(?int $purposeId): self
    {
        $this->purposeId = $purposeId;
        return $this;
    }
}