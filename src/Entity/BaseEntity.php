<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modified_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $created_user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $modified_user;

    /**
     * @var bool|null
     */
    public $sluggable;


    public function __construct()
    {
        $this->sluggable = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeInterface $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function getCreatedUser(): ?int
    {
        return $this->created_user;
    }

    public function setCreatedUser(int $created_user): self
    {
        $this->created_user = $created_user;

        return $this;
    }

    public function getModifiedUser(): ?int
    {
        return $this->modified_user;
    }

    public function setModifiedUser(int $modified_user): self
    {
        $this->modified_user = $modified_user;

        return $this;
    }

    public function getSluggable(): ?bool
    {
        return $this->sluggable;
    }
}
