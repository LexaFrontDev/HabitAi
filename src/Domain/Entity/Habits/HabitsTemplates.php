<?php

namespace App\Domain\Entity\Habits;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'habits_templates')]
class HabitsTemplates
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'title_template', type: 'string', length: 255)]
    private string $title_template;

    #[ORM\Column(name: 'quote_template', type: 'string')]
    private string $quote_template;

    #[ORM\Column(name: 'notification_template', type: 'string')]
    private string $notification_template;

    #[ORM\Column(name: 'dates_type_template', type: 'string')]
    private string $dates_type_template;


    // / getters setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleTemplate(): string
    {
        return $this->title_template;
    }

    public function setTitleTemplate(string $title_template): void
    {
        $this->title_template = $title_template;
    }

    public function getQuoteTemplate(): string
    {
        return $this->quote_template;
    }

    public function setQuoteTemplate(string $quote_template): void
    {
        $this->quote_template = $quote_template;
    }

    public function getDatesTypeTemplate(): string
    {
        return $this->dates_type_template;
    }

    public function setDatesTypeTemplate(string $dates_type_template): void
    {
        $this->dates_type_template = $dates_type_template;
    }

    public function getNotificationTemplate(): string
    {
        return $this->notification_template;
    }

    public function setNotificationTemplate(string $notification_template): void
    {
        $this->notification_template = $notification_template;
    }
}
