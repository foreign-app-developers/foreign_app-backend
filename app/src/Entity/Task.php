<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 *
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="json")
     */
    private $answers;

    /**
     * @ORM\Column(type="json")
     */
    private $rightAnswers;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;


    /**
     *
     * @param array $givenAnswers
     * @return
     */
    public function checkAnswers(array $givenAnswers)
    {
        // проверка ответов
    }
    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }
}
