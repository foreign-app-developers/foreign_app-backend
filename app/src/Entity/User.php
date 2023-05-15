<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    private $isTeacher;
    private string $username;
    private $password;
    private $isDirector;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;

    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if ($this->isTeacher) {
            $roles[] = 'ROLE_TEACHER';
        }
        if ($this->isDirector) {
            $roles[] = 'ROLE_DIRECTOR';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Проверяем, имеет ли пользователь роль "teacher"
     *
     * @return bool
     */
    public function isTeacher(): bool
    {
        return \in_array('ROLE_TEACHER', $this->getRoles(), true);
    }
    /**
     * Проверяем, имеет ли пользователь роль "teacher"
     *
     * @return bool
     */
    public function isDirector(): bool
    {
        return \in_array('ROLE_DIRECTOR', $this->getRoles(), true);
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }
}
