<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sessionId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=CardProduct::class, mappedBy="card", orphanRemoval=true)
     */
    private $cardProducts;

    public function __construct()
    {
        $this->createdAt =  new \DateTimeImmutable();
        $this->cardProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|CardProduct[]
     */
    public function getCardProducts(): Collection
    {
        return $this->cardProducts;
    }

    public function addCardProduct(CardProduct $cardProduct): self
    {
        if (!$this->cardProducts->contains($cardProduct)) {
            $this->cardProducts[] = $cardProduct;
            $cardProduct->setCard($this);
        }

        return $this;
    }

    public function removeCardProduct(CardProduct $cardProduct): self
    {
        if ($this->cardProducts->removeElement($cardProduct)) {
            // set the owning side to null (unless already changed)
            if ($cardProduct->getCard() === $this) {
                $cardProduct->setCard(null);
            }
        }

        return $this;
    }
}
