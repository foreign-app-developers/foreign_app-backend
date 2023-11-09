<?php

namespace App\Entity;

use App\Repository\TaskUserAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskUserAnswerRepository::class)
 *
 */
class TaskUserAnswer
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="taskUserAnswers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="json")
     */
    private $answer;
    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }
}
