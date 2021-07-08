<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({"event" = "Event", "birthday" = "Birthday", "wedding" = "Wedding", "mourning" = "Mourning"})
 */
abstract class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $eventName;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $eventDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private \DateTimeInterface $eventTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $eventAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $eventPostalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $eventCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $eventCountry;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $eventDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $eventCreatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="eventId")
     */
    private Collection $messages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $eventPicture;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getEventTime(): ?\DateTimeInterface
    {
        return $this->eventTime;
    }

    public function setEventTime(\DateTimeInterface $eventTime): self
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    public function getEventAddress(): ?string
    {
        return $this->eventAddress;
    }

    public function setEventAddress(string $eventAddress): self
    {
        $this->eventAddress = $eventAddress;

        return $this;
    }

    public function getEventPostalCode(): ?string
    {
        return $this->eventPostalCode;
    }

    public function setEventPostalCode(string $eventPostalCode): self
    {
        $this->eventPostalCode = $eventPostalCode;

        return $this;
    }

    public function getEventCity(): ?string
    {
        return $this->eventCity;
    }

    public function setEventCity(string $eventCity): self
    {
        $this->eventCity = $eventCity;

        return $this;
    }

    public function getEventCountry(): ?string
    {
        return $this->eventCountry;
    }

    public function setEventCountry(string $eventCountry): self
    {
        $this->eventCountry = $eventCountry;

        return $this;
    }

    public function getEventDescription(): ?string
    {
        return $this->eventDescription;
    }

    public function setEventDescription(string $eventDescription): self
    {
        $this->eventDescription = $eventDescription;

        return $this;
    }

    public function getEventCreatedAt(): ?\DateTimeInterface
    {
        return $this->eventCreatedAt;
    }

    public function setEventCreatedAt(\DateTimeInterface $eventCreatedAt): self
    {
        $this->eventCreatedAt = $eventCreatedAt;

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
            $message->setEventId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getEventId() === $this) {
                $message->setEventId(null);
            }
        }

        return $this;
    }

    public function getEventPicture(): ?string
    {
        return $this->eventPicture;
    }

    public function setEventPicture(string $eventPicture): self
    {
        $this->eventPicture = $eventPicture;

        return $this;
    }
}
