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
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userTeacher")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="teacherUser")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    // Add any additional properties and methods relevant to UserTeacher entity

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getTeacher()
    {
        return $this->teacher;
    }

    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }
}
