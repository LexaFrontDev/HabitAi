<?php

namespace App\Domain\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_notification_settings')]
class UserNotificationSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false)]
    private int $user_id;


    #[ORM\Column(name: 'email_enabled', type: 'boolean', nullable: false)]
    private bool $email_enabled = false;


    #[ORM\Column(name: 'web_enabled', type: 'boolean', nullable: false)]
    private bool $web_enabled = true;

    #[ORM\Column(name: 'push_enabled', type: 'boolean', nullable: false)]
    private bool $push_enabled = false;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['comment' => 'Дата создания записи'])]
    private \DateTimeInterface $created_at;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_deleted', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_deleted = false;

    public function __construct(int $user_id, bool $email_enabled, bool $web_enabled, bool $push_enabled)
    {
        $this->user_id = $user_id;
        $this->email_enabled = $email_enabled;
        $this->web_enabled = $web_enabled;
        $this->push_enabled = $push_enabled;
        $this->created_at = new \DateTime();
    }

    public function setUpdatedAt(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
