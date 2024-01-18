<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(type="string", nullable=false,unique=true)
     * @var string
     */
    private string $email;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="sender", cascade={ "persist", "remove" }, orphanRemoval=true)
     * @var Collection
     */
    private Collection $invitationsSent;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="recipient", cascade={ "persist", "remove" }, orphanRemoval=true)
     * @var Collection
     */
    private Collection $invitationsReceived;

    public function __construct()
    {
        $this->invitationsSent = new ArrayCollection();
        $this->invitationsReceived = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getInvitationsSent(): Collection
    {
        return $this->invitationsSent;
    }

    public function setInvitationsSent(Collection $invitationsSent): User
    {
        $this->invitationsSent = $invitationsSent;

        return $this;
    }

    public function addInvitationsSent(Invitation $invitation): User
    {
        $invitation->setSender($this);
        $this->invitationsSent->add($invitation);

        return $this;
    }

    public function removeInvitationsSent(Invitation $invitation): User
    {
        $this->invitationsSent->removeElement($invitation);

        return $this;
    }

    public function getInvitationsReceived(): Collection
    {
        return $this->invitationsReceived;
    }

    public function setInvitationsReceived(Collection $invitationsReceived): User
    {
        $this->invitationsReceived = $invitationsReceived;

        return $this;
    }

    public function addInvitationsReceived(Invitation $invitation): User
    {
        $invitation->setRecipient($this);
        $this->invitationsReceived->add($invitation);

        return $this;
    }

    public function removeInvitationsReceived(Invitation $invitation): User
    {
        $this->invitationsReceived->removeElement($invitation);

        return $this;
    }
}
