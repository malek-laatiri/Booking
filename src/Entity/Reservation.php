<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\Entity
 * @ORM\Table(name="reservation")
 */
class Reservation
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
    private $dateReservation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateArrivee;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDepart;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $voucher;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrAdulte;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrEnfant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facture", inversedBy="reservation",cascade={"persist"})
     */
    private $facture;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="reservation",cascade={"persist"})
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="reservation")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Extras", mappedBy="reservation")
     */
    private $extras;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chambre", inversedBy="reservation")
     */
    private $chambre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reduction", inversedBy="reservations",cascade={"persist"})
     */
    private $reduction;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user", mappedBy="reservation")
     */
    private $createdby;

    public function __construct()
    {
        $this->extras = new ArrayCollection();
        $this->client = new ArrayCollection();
        $this->reduction= new ArrayCollection();
        $this->dateReservation = new \DateTime();
        $this->voucher=str_shuffle(random_int(1,9999).str_shuffle("AzBtXbYnZ"));
        $this->createdby = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getVoucher(): ?string
    {
        return $this->voucher;
    }

    public function setVoucher(string $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function getNbrAdulte(): ?int
    {
        return $this->nbrAdulte;
    }

    public function setNbrAdulte(int $nbrAdulte): self
    {
        $this->nbrAdulte = $nbrAdulte;

        return $this;
    }

    public function getNbrEnfant(): ?int
    {
        return $this->nbrEnfant;
    }

    public function setNbrEnfant(int $nbrEnfant): self
    {
        $this->nbrEnfant = $nbrEnfant;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }



    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Extras[]
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(Extras $extra): self
    {
        if (!$this->extras->contains($extra)) {
            $this->extras[] = $extra;
            $extra->addReservation($this);
        }

        return $this;
    }

    public function removeExtra(Extras $extra): self
    {
        if ($this->extras->contains($extra)) {
            $this->extras->removeElement($extra);
            $extra->removeReservation($this);
        }

        return $this;
    }

    public function addClient(Client $extra): self
    {
        if (!$this->client->contains($extra)) {
            $this->client[] = $extra;
            $extra->addReservation($this);
        }

        return $this;
    }

    public function removeClient(Client $extra): self
    {
        if ($this->client->contains($extra)) {
            $this->client->removeElement($extra);
            $extra->removeReservation($this);
        }

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReduction()
    {
        return $this->reduction;
    }



    public function setReduction(?Reduction $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function addReduction(Reduction $extra): self
    {
        if (!$this->reduction->contains($extra)) {
            $this->reduction[] = $extra;
            $extra->addReservation($this);
        }

        return $this;
    }

    public function removeReduction(Reduction $extra): self
    {
        if ($this->reduction->contains($extra)) {
            $this->reduction->removeElement($extra);
            $extra->removeReservation($this);
        }

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getCreatedby(): Collection
    {
        return $this->createdby;
    }

    public function addCreatedby(user $createdby): self
    {
        if (!$this->createdby->contains($createdby)) {
            $this->createdby[] = $createdby;
            $createdby->setReservation($this);
        }

        return $this;
    }

    public function removeCreatedby(user $createdby): self
    {
        if ($this->createdby->contains($createdby)) {
            $this->createdby->removeElement($createdby);
            // set the owning side to null (unless already changed)
            if ($createdby->getReservation() === $this) {
                $createdby->setReservation(null);
            }
        }

        return $this;
    }

    /**
     * @param mixed $createdby
     */
    public function setCreatedby($createdby): void
    {
        $this->createdby = $createdby;
    }



}
