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
    private string $husbandFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $husbandLastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $wifeFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $wifeLastname;

    public function getHusbandFirstname(): ?string
    {
        return $this->husbandFirstname;
    }

    public function setHusbandFirstname(string $husbandFirstname): self
    {
        $this->husbandFirstname = $husbandFirstname;

        return $this;
    }

    public function getHusbandLastname(): ?string
    {
        return $this->husbandLastname;
    }

    public function setHusbandLastname(string $husbandLastname): self
    {
        $this->husbandLastname = $husbandLastname;

        return $this;
    }

    public function getWifeFirstname(): ?string
    {
        return $this->wifeFirstname;
    }

    public function setWifeFirstname(string $wifeFirstname): self
    {
        $this->wifeFirstname = $wifeFirstname;

        return $this;
    }

    public function getWifeLastname(): ?string
    {
        return $this->wifeLastname;
    }

    public function setWifeLastname(string $wifeLastname): self
    {
        $this->wifeLastname = $wifeLastname;

        return $this;
    }
}
