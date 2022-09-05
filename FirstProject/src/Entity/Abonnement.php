<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\NullLogger;

/**
 * @ORM\Entity(repositoryClass=AbonnementRepository::class)
 */
class Abonnement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;
    /**
     * @ORM\Column(type="boolean")
     */
    private $etat = false;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $duree ;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $nbrDevice ;

    public function getNbrDevice(): ?int
    {
        return $this->nbrDevice;
    }

    public function setNbrDevice(int $d): self
    {
        $this->nbrDevice = $d;

        return $this;
    }
    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getEtat(): bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Device",
     *     mappedBy="Abonnements")
     */
    private $Devices;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateexp;

    /**
     * @ORM\Column(type="integer")
     */
    private $enatentte;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="abonnements")
     */
    private $offre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $chef;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbracces;

    /**
     * @ORM\ManyToMany(targetEntity=Client::class, inversedBy="abonnements")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=DomaineApplication::class, inversedBy="abonnements")
     */
    private $DomaineApplication;

    public function __construct()
    {
        $this->User = new ArrayCollection();
    }

    public function getDevices(): ?Device
    {
        return $this->Devices;
    }

    public function setDevices(Device $device): self
    {
        $this->Devices = $device;

        return $this;
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

    public function getDateexp(): ?\DateTimeInterface
    {
        return $this->dateexp;
    }

    public function setDateexp(\DateTimeInterface $dateexp): self
    {
        $this->dateexp = $dateexp;

        return $this;
    }

    public function getEnatentte(): ?int
    {
        return $this->enatentte;
    }

    public function setEnatentte(int $enatentte): self
    {
        $this->enatentte = $enatentte;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getChef(): ?string
    {
        return $this->chef;
    }

    public function setChef(string $chef): self
    {
        $this->chef = $chef;

        return $this;
    }

    public function getNbracces(): ?int
    {
        return $this->nbracces;
    }

    public function setNbracces(?int $nbracces): self
    {
        $this->nbracces = $nbracces;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(Client $User): self
    {
        if (!$this->User->contains($User)) {
            $this->User[] = $User;
        }

        return $this;
    }

    public function removeUser(Client $User): self
    {
        $this->User->removeElement($User);

        return $this;
    }

    public function getDomaineApplication(): ?DomaineApplication
    {
        return $this->DomaineApplication;
    }

    public function setDomaineApplication(?DomaineApplication $DomaineApplication): self
    {
        $this->DomaineApplication = $DomaineApplication;

        return $this;
    }

}
