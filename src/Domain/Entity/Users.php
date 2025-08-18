<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Settings\SettingsUsersValueObject;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    private string $name;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(length: 255, nullable: false)]
    private string $password;

    #[ORM\Column(type: 'integer')]
    private int $premium = 1;

    #[ORM\Column(length: 35)]
    private string $role = 'user';



    /** @var SettingsUsersValueObject[]|null */
    #[ORM\Column(name: 'users_settings', type: 'json', nullable: true)]
    private ?array $settings = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_new = true;

    #[ORM\Column(type: 'integer')]
    private int $email_check = 0;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $user_country = null;

    #[ORM\Column(type: 'integer')]
    private int $is_lang = 0;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата создания записи'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPremium(): int
    {
        return $this->premium;
    }

    public function setPremium(int $premium): self
    {
        $this->premium = $premium;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return SettingsUsersValueObject[]|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * @param SettingsUsersValueObject[]|null $settings
     *
     * @return $this
     */
    public function setSettings(?array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function isNew(): bool
    {
        return $this->is_new;
    }

    public function setIsNew(bool $is_new): self
    {
        $this->is_new = $is_new;

        return $this;
    }

    public function getEmailCheck(): int
    {
        return $this->email_check;
    }

    public function setEmailCheck(int $email_check): self
    {
        $this->email_check = $email_check;

        return $this;
    }

    public function getUserCountry(): ?string
    {
        return $this->user_country;
    }

    public function setUserCountry(?string $user_country): self
    {
        $this->user_country = $user_country;

        return $this;
    }

    public function getIsLang(): int
    {
        return $this->is_lang;
    }

    public function setIsLang(int $is_lang): self
    {
        $this->is_lang = $is_lang;

        return $this;
    }

    public function getRoles(): array
    {
        return array_unique([$this->role, 'ROLE_USER']);
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
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

    public function isDeleted(): bool
    {
        return $this->is_delete;
    }

    public function setIsDeleted(bool $is_delete): self
    {
        $this->is_delete = $is_delete;

        return $this;
    }
}
