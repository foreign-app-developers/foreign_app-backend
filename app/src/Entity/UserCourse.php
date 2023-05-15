<?php

namespace App\Entity;

use App\Repository\UserCourseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserCourseRepository::class)
 */
class UserCourse
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="userCourse")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userCourse")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    // Add any additional properties and methods relevant to UserCourse entity

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course)
    {
        $this->course = $course;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
