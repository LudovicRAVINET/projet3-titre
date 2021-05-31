<?php

namespace App\Entity;

use App\Repository\WeddingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeddingRepository::class)
 */
class Wedding extends Event
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $wSpouse1Firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $wSpouse1Lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $wSpouse2Firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $wSpouse2Lastname;

    public function getWSpouse1Firstname(): ?string
    {
        return $this->wSpouse1Firstname;
    }

    public function setWSpouse1Firstname(string $wSpouse1Firstname): self
    {
        $this->wSpouse1Firstname = $wSpouse1Firstname;

        return $this;
    }

    public function getWSpouse1Lastname(): ?string
    {
        return $this->wSpouse1Lastname;
    }

    public function setWSpouse1Lastname(string $wSpouse1Lastname): self
    {
        $this->wSpouse1Lastname = $wSpouse1Lastname;

        return $this;
    }

    public function getWSpouse2Firstname(): ?string
    {
        return $this->wSpouse2Firstname;
    }

    public function setWSpouse2Firstname(string $wSpouse2Firstname): self
    {
        $this->wSpouse2Firstname = $wSpouse2Firstname;

        return $this;
    }

    public function getWSpouse2Lastname(): ?string
    {
        return $this->wSpouse2Lastname;
    }

    public function setWSpouse2Lastname(string $wSpouse2Lastname): self
    {
        $this->wSpouse2Lastname = $wSpouse2Lastname;

        return $this;
    }
}
