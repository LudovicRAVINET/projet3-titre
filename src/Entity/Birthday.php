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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $birthdayFirstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $birthdayLastname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private \DateTimeInterface $birthdayDate;

    public function getBirthdayFirstname(): ?string
    {
        return $this->birthdayFirstname;
    }

    public function setBirthdayFirstname(string $birthdayFirstname): self
    {
        $this->birthdayFirstname = $birthdayFirstname;

        return $this;
    }

    public function getBirthdayLastname(): ?string
    {
        return $this->birthdayLastname;
    }

    public function setBirthdayLastname(string $birthdayLastname): self
    {
        $this->birthdayLastname = $birthdayLastname;

        return $this;
    }

    public function getBirthdayDate(): ?\DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(\DateTimeInterface $birthdayDate): self
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }
}
