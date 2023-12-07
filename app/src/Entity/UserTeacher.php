<?php

namespace App\Entity;

use App\Repository\UserTeacherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTeacherRepository::class)
 */
class UserTeacher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;
    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $accept;

    /**
     * @ORM\Column(type="integer")
     */
    private $teacher_id;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
    public function getAccept(): ?bool
    {
        return $this->accept;
    }

    public function setAccept(bool $accept): self
    {
        $this->accept = $accept;

        return $this;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacher_id;
    }

    public function setTeacherId(int $teacher_id): self
    {
        $this->teacher_id = $teacher_id;

        return $this;
    }
}
