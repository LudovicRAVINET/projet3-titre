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
     * @ORM\Column(type="string", length=255)
     */
    private string $mFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $mLastname;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $mDeathDate;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $mBirthDate;

    /**
     * @ORM\Column(type="text")
     */
    private string $mBiography;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $mRelationship;

    public function getMFirstname(): ?string
    {
        return $this->mFirstname;
    }

    public function setMFirstname(string $mFirstname): self
    {
        $this->mFirstname = $mFirstname;

        return $this;
    }

    public function getMLastname(): ?string
    {
        return $this->mLastname;
    }

    public function setMLastname(string $mLastname): self
    {
        $this->mLastname = $mLastname;

        return $this;
    }

    public function getMDeathDate(): ?\DateTimeInterface
    {
        return $this->mDeathDate;
    }

    public function setMDeathDate(\DateTimeInterface $mDeathDate): self
    {
        $this->mDeathDate = $mDeathDate;

        return $this;
    }

    public function getMBirthDate(): ?\DateTimeInterface
    {
        return $this->mBirthDate;
    }

    public function setMBirthDate(\DateTimeInterface $mBirthDate): self
    {
        $this->mBirthDate = $mBirthDate;

        return $this;
    }

    public function getMBiography(): ?string
    {
        return $this->mBiography;
    }

    public function setMBiography(string $mBiography): self
    {
        $this->mBiography = $mBiography;

        return $this;
    }

    public function getMRelationship(): ?string
    {
        return $this->mRelationship;
    }

    public function setMRelationship(?string $mRelationship): self
    {
        $this->mRelationship = $mRelationship;

        return $this;
    }
}
