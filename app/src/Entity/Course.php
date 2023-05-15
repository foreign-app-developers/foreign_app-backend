<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ?int $created_by = null;

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
     * @ORM\OneToMany(targetEntity="Material", mappedBy="course")
     */
    private $materials;

    /**
     * @ORM\OneToMany(targetEntity="UserCourse", mappedBy="course")
     */
    private $userCourses;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="course")
     */
    private $tasks;
    public function __construct()
    {
        $this->materials = new ArrayCollection();
        $this->userCourses = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getMaterials()
    {
        return $this->materials;
    }

    public function addMaterial(Material $material)
    {
        $this->materials[] = $material;
        $material->setCourse($this);
    }

    public function removeMaterial(Material $material)
    {
        $this->materials->removeElement($material);
        $material->setCourse(null);
    }



    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): self
    {
        $this->created_by = $created_by;

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

}
