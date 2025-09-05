<?php

namespace App\Domain\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_push_subscriptions')]
class UserPushSubscriptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false)]
    private int $user_id;

    #[ORM\Column(name: 'platform', type: 'string', length: 130, nullable: false)]
    private string $platform;


    #[ORM\Column(name: 'endpoint', type: 'text', nullable: false)]
    private string $endpoint;


    /**
     * @var string[]
     */
    #[ORM\Column(name: 'keys', type: 'json', nullable: false)]
    private array $keys;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['comment' => 'Дата создания записи'])]
    private \DateTimeInterface $created_at;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_deleted', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_deleted = false;

    /**
     * @param string[] $keys
     */
    public function __construct(int $userId, string $platform, string $endpoint, array $keys)
    {
        $this->user_id = $userId;
        $this->platform = $platform;
        $this->endpoint = $endpoint;
        $this->keys = $keys;
        $this->created_at = new \DateTime();
    }

    public function setUpdatedAt(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    /**
     * @param array<string, string> $keys
     */
    public function updateEndPoint(string $endpoint, array $keys): void
    {
        $this->setUpdatedAt();
        $this->endpoint = $endpoint;
        $this->keys = $keys;
    }
}
