<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="le nombre de device est obligatoire")
     */
    private $nbrdevice;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Durée est obligatoire")
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Nom est obligatoire")
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=DomaineApplication::class, inversedBy="offres")
     */
    private $domaineApplication;

    /**
     * @ORM\OneToMany(targetEntity=Abonnement::class, mappedBy="offre")
     */
    private $abonnements;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="nombre d'acces est obligatoire")
     */
    private $nbrAcces;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrdevice(): ?int
    {
        return $this->nbrdevice;
    }

    public function setNbrdevice(int $nbrdevice): self
    {
        $this->nbrdevice = $nbrdevice;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDomaineApplication(): ?DomaineApplication
    {
        return $this->domaineApplication;
    }

    public function setDomaineApplication(?DomaineApplication $domaineApplication): self
    {
        $this->domaineApplication = $domaineApplication;

        return $this;
    }

    /**
     * @return Collection<int, abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setOffre($this);
        }

        return $this;
    }

    public function removeAbonnement(abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getOffre() === $this) {
                $abonnement->setOffre(null);
            }
        }

        return $this;
    }

    public function getNbrAcces(): ?int
    {
        return $this->nbrAcces;
    }

    public function setNbrAcces(int $nbrAcces): self
    {
        $this->nbrAcces = $nbrAcces;

        return $this;
    }
}
