<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HotelRepository")
 */
class Hotel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $TVA;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reduction", mappedBy="hotel")
     */
    private $reduction;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chambre", mappedBy="hotel")
     */
    private $chambres;

    public function __toString()
    {
        return $this->nom;
      }

    public function __construct()
    {
        $this->reduction = new ArrayCollection();
        $this->chambres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTVA(): ?int
    {
        return $this->TVA;
    }

    public function setTVA(int $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    /**
     * @return Collection|Reduction[]
     */
    public function getReduction(): Collection
    {
        return $this->reduction;
    }

    public function addReduction(Reduction $reduction): self
    {
        if (!$this->reduction->contains($reduction)) {
            $this->reduction[] = $reduction;
            $reduction->setHotel($this);
        }

        return $this;
    }

    public function removeReduction(Reduction $reduction): self
    {
        if ($this->reduction->contains($reduction)) {
            $this->reduction->removeElement($reduction);
            // set the owning side to null (unless already changed)
            if ($reduction->getHotel() === $this) {
                $reduction->setHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chambre[]
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres[] = $chambre;
            $chambre->setHotel($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambres->contains($chambre)) {
            $this->chambres->removeElement($chambre);
            // set the owning side to null (unless already changed)
            if ($chambre->getHotel() === $this) {
                $chambre->setHotel(null);
            }
        }

        return $this;
    }

}
