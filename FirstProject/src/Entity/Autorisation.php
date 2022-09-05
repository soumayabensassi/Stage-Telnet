<?php

namespace App\Entity;

use App\Repository\AutorisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutorisationRepository::class)
 */
class Autorisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $bilanhydrique;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $temperatureAgrigole;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $temperatureSante;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $blood;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $heartbeat;

    /**
     * @ORM\OneToOne(targetEntity=Device::class, cascade={"persist", "remove"})
     */
    private $device;

    public function __construct()
    {
        $this->device = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function isBilanhydrique(): ?bool
    {
        return $this->bilanhydrique;
    }

    public function setBilanhydrique(bool $bilanhydrique): self
    {
        $this->bilanhydrique = $bilanhydrique;

        return $this;
    }

    public function isTemperatureAgrigole(): ?bool
    {
        return $this->temperatureAgrigole;
    }

    public function setTemperatureAgrigole(bool $temperatureAgrigole): self
    {
        $this->temperatureAgrigole = $temperatureAgrigole;

        return $this;
    }

    public function isTemperatureSante(): ?bool
    {
        return $this->temperatureSante;
    }

    public function setTemperatureSante(bool $temperatureSante): self
    {
        $this->temperatureSante = $temperatureSante;

        return $this;
    }

    public function isBlood(): ?bool
    {
        return $this->blood;
    }

    public function setBlood(bool $blood): self
    {
        $this->blood = $blood;

        return $this;
    }

    public function isHeartbeat(): ?bool
    {
        return $this->heartbeat;
    }

    public function setHeartbeat(bool $heartbeat): self
    {
        $this->heartbeat = $heartbeat;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }
}
