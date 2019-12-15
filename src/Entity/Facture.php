<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFacture;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     */
    private $numFact;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="facture")
     */
    private $reservation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="factures",)
     */
    private $modifiedby;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $modifydate;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->dateFacture = new \DateTime();
        $this->numFact= random_int(1,99999);

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTimeInterface $dateFacture): self
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNumFact(): ?int
    {
        return $this->numFact;
    }

    public function setNumFact(int $numFact): self
    {
        $this->numFact = $numFact;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setFacture($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->contains($reservation)) {
            $this->reservation->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getFacture() === $this) {
                $reservation->setFacture(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->montant;
    }

    public function getModifiedby(): ?User
    {
        return $this->modifiedby;
    }

    public function setModifiedby(?User $modifiedby): self
    {
        $this->modifiedby = $modifiedby;

        return $this;
    }

    public function getModifydate(): ?\DateTimeInterface
    {
        return $this->modifydate;
    }

    public function setModifydate(\DateTimeInterface $modifydate): self
    {
        $this->modifydate = $modifydate;

        return $this;
    }

}
