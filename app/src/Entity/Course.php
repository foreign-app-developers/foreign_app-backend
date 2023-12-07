<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 * @ORM\Table(name="courses")
 */

class Course
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
    private ?int $author_id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $status = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $description = null;

    /**
     * @ORM\OneToMany(targetEntity=CourseForUser::class, mappedBy="course", cascade={"remove"})
     */
    private $courseForUsers;
    public function __construct()
    {
        $this->courseForUsers = new ArrayCollection();
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    /**
     * @return Collection|CourseForUser[]
     */
    public function getCourseForUsers(): Collection
    {
        /** @var Collection|CourseForUser[] $collection */
        $collection = $this->courseForUsers;
        return $collection;
    }


    public function addCourseForUser(CourseForUser $courseForUser): self
    {
        if (!$this->courseForUsers->contains($courseForUser)) {
            $this->courseForUsers[] = $courseForUser;
            $courseForUser->setCourse($this);
        }

        return $this;
    }

    public function removeCourseForUser(CourseForUser $courseForUser): self
    {
        if ($this->courseForUsers->removeElement($courseForUser)) {
            // set the owning side to null (unless already changed)
            if ($courseForUser->getCourse() === $this) {
                $courseForUser->setCourse(null);
            }
        }

        return $this;
    }
}
