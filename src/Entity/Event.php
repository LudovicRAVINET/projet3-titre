<?php

namespace App\Entity;

use App\Repository\EventRepository;
use App\Entity\Type;
use App\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     */
    private string $title;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     * @Assert\GreaterThanOrEqual(
     *      value="today",
     *      message="La date de l'événement doit être supérieure ou égale à la date de sa saisie."
     * )
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private ?\DateTimeInterface $time;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     */
    private User $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $hasJackpot;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="events")
     * @Assert\NotBlank(message="Veuillez saisir une valeur.")
     */
    private Type $type;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="event")
     */
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getTitle(): ?string
    {
        return $this->title ?? '';
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time ?? null;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image ?? '';
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getHasJackpot(): ?bool
    {
        return $this->hasJackpot ?? null;
    }

    public function setHasJackpot(?bool $hasJackpot): self
    {
        $this->hasJackpot = $hasJackpot;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type ?? null;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setEvent($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getEvent() === $this) {
                $message->setEvent($message->getEvent());
            }
        }

        return $this;
    }

    public function getDateFr(): ?string
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $dateFr = strftime("%A %e %B %Y", $this->date->getTimestamp());
        if ($dateFr === false) {
            return 'date invalide';
        }

        return $dateFr;
    }
}
