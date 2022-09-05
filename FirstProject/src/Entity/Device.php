<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 * @UniqueEntity(fields={"serialnumber"}, message="There is already an Device with this serialnumber")
 */
class Device
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50,unique=true)
     * @Assert\NotBlank(message="Serial number est obligatoire")

     */
    private $serialnumber;
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Designiation est obligatoire")

     */
    private $designiation;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }

    public function setSerialnumber(string $serialnumber): self
    {
        $this->serialnumber = $serialnumber;

        return $this;
    }
    public function getDesigniation(): ?string
    {
        return $this->designiation;
    }

    public function setDesigniation(string $designiation): self
    {
        $this->designiation = $designiation;

        return $this;
    }
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Abonnement",
     *     inversedBy="Devices")
          */
    private $Abonnements;

    /**
     * @ORM\ManyToOne(targetEntity=DomaineApplication::class, inversedBy="devices")
     */
    private $domaineApplication;

    /**
     * @ORM\OneToMany(targetEntity=Donnee::class, mappedBy="device")
     */
    private $donnee;


    public function getAbonnements(): ?Abonnement
    {
        return $this->Abonnements;
    }

    public function setAbonnements(Abonnement $abonnement): self
    {
        $this->Abonnements = $abonnement;

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

    public function getDonnee(): ?Donnee
    {
        return $this->donnee;
    }

    public function setDonnee(?Donnee $donnee): self
    {
        $this->donnee = $donnee;

        return $this;
    }


}
