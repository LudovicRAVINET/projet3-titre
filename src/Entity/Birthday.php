<?php

namespace App\Entity;

use App\Repository\BirthdayRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BirthdayRepository::class)
 */
class Birthday extends Event
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $bFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $bLastname;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $bBirthDate;

    public function getBFirstname(): ?string
    {
        return $this->bFirstname;
    }

    public function setBFirstname(string $bFirstname): self
    {
        $this->bFirstname = $bFirstname;

        return $this;
    }

    public function getBLastname(): ?string
    {
        return $this->bLastname;
    }

    public function setBLastname(string $bLastname): self
    {
        $this->bLastname = $bLastname;

        return $this;
    }

    public function getBBirthDate(): ?\DateTimeInterface
    {
        return $this->bBirthDate;
    }

    public function setBBirthDate(\DateTimeInterface $bBirthDate): self
    {
        $this->bBirthDate = $bBirthDate;

        return $this;
    }
}
