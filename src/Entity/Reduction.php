<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReductionRepository")
 */
class Reduction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $codeReduction;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $taux;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hotel", inversedBy="reduction")
     */
    private $hotel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="reduction")
     */
    private $reservations;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $csv;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isexpired;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->isexpired=false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeReduction(): ?string
    {
        return $this->codeReduction;
    }

    public function setCodeReduction(string $codeReduction): self
    {
        $this->codeReduction = $codeReduction;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTaux(): ?int
    {
        return $this->taux;
    }

    public function setTaux(int $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setReduction($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getReduction() === $this) {
                $reservation->setReduction(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->taux;
      }

    public function getCsv(): ?string
    {
        return $this->csv;
    }

    public function setCsv(string $csv): self
    {
        $this->csv = $csv;

        return $this;
    }

    public function getIsexpired(): ?bool
    {
        return $this->isexpired;
    }

    public function setIsexpired(bool $isexpired): self
    {
        $this->isexpired = $isexpired;

        return $this;
    }

}
