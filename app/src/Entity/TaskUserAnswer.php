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
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="text")
     */
    private $answer;
    public function getTask()
    {
        return $this->task;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
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
