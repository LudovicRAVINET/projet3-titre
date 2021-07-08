<?php

namespace App\Entity;

use App\Repository\MourningRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MourningRepository::class)
 */
class Mourning extends Event
{


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $deadFirstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $deadLastname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private \DateTimeInterface $deadBirthDay;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private \DateTimeInterface $deadDeathDay;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $deadBiography;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $relationship;


    public function getDeadFirstname(): ?string
    {
        return $this->deadFirstname;
    }

    public function setDeadFirstname(string $deadFirstname): self
    {
        $this->deadFirstname = $deadFirstname;

        return $this;
    }

    public function getDeadLastname(): ?string
    {
        return $this->deadLastname;
    }

    public function setDeadLastname(string $deadLastname): self
    {
        $this->deadLastname = $deadLastname;

        return $this;
    }

    public function getDeadBirthDay(): ?\DateTimeInterface
    {
        return $this->deadBirthDay;
    }

    public function setDeadBirthDay(\DateTimeInterface $deadBirthDay): self
    {
        $this->deadBirthDay = $deadBirthDay;

        return $this;
    }

    public function getDeadDeathDay(): ?\DateTimeInterface
    {
        return $this->deadDeathDay;
    }

    public function setDeadDeathDay(\DateTimeInterface $deadDeathDay): self
    {
        $this->deadDeathDay = $deadDeathDay;

        return $this;
    }

    public function getDeadBiography(): ?string
    {
        return $this->deadBiography;
    }

    public function setDeadBiography(string $deadBiography): self
    {
        $this->deadBiography = $deadBiography;

        return $this;
    }

    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    public function setRelationship(string $relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }
}
