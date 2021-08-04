<?php

namespace App\Entity;

use App\Repository\NoticeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoticeRepository::class)
 */
class Notice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      notInRangeMessage = "La note doit être comprise entre 1 et 5.",
     * )
     */
    private int $note;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     * @Assert\LessThanOrEqual(
     *      value="now",
     *      message="La date du commentaire doit être inférieure ou égale à la date de sa saisie."
     * )
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     */
    private string $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notices")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     */
    private User $user;

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getNote(): ?int
    {
        return $this->note ?? null;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date ?? null;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment ?? '';
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user ?? null;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
