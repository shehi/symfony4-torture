<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var null|bool
     */
    private ?bool $isAccepted = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitationsSent")
     * @var User
     */
    private User $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitationsReceived")
     * @var User
     */
    private User $recipient;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): Invitation
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function setSender(User $user): self
    {
        $this->sender = $user;

        return $this;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function setRecipient(User $user): self
    {
        $this->recipient = $user;

        return $this;
    }
}
