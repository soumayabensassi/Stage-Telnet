<?php

namespace App\Entity;

use App\Repository\DomaineApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomaineApplicationRepository::class)
 */
class DomaineApplication
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
    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;



    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="domaineApplication")
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity=Device::class, mappedBy="domaineApplication")
     */
    private $devices;

    /**
     * @ORM\OneToMany(targetEntity=Abonnement::class, mappedBy="DomaineApplication")
     */
    private $abonnements;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->devices = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    /**
     * @return Collection<int, offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setDomaineApplication($this);
        }

        return $this;
    }

    public function removeOffre(offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getDomaineApplication() === $this) {
                $offre->setDomaineApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
            $device->setDomaineApplication($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->removeElement($device)) {
            // set the owning side to null (unless already changed)
            if ($device->getDomaineApplication() === $this) {
                $device->setDomaineApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setDomaineApplication($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getDomaineApplication() === $this) {
                $abonnement->setDomaineApplication(null);
            }
        }

        return $this;
    }
}
