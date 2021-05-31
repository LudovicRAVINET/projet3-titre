<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $messageText;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $messageAuthor;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $messageDateTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $mediaUrl;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $eventId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageText(): ?string
    {
        return $this->messageText;
    }

    public function setMessageText(string $messageText): self
    {
        $this->messageText = $messageText;

        return $this;
    }

    public function getMessageAuthor(): ?string
    {
        return $this->messageAuthor;
    }

    public function setMessageAuthor(string $messageAuthor): self
    {
        $this->messageAuthor = $messageAuthor;

        return $this;
    }

    public function getMessageDateTime(): ?\DateTimeInterface
    {
        return $this->messageDateTime;
    }

    public function setMessageDateTime(\DateTimeInterface $messageDateTime): self
    {
        $this->messageDateTime = $messageDateTime;

        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->mediaUrl;
    }

    public function setMediaUrl(string $mediaUrl): self
    {
        $this->mediaUrl = $mediaUrl;

        return $this;
    }

    public function getEventId(): ?Event
    {
        return $this->eventId;
    }

    public function setEventId(?Event $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }
}
