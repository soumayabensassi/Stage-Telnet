<?php

namespace App\Entity;

use App\Repository\DonneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonneeRepository::class)
 */
class Donnee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string")
     */
    private $data;

    /**
     * @ORM\ManyToOne(targetEntity=Device::class, inversedBy="donnee")
     */
    private $device;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;



    public function __construct()
    {
        $this->device = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getData()
    {
        return $this->data;
    }

    public function setData($data): string
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevice(): Collection
    {
        return $this->device;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->device->contains($device)) {
            $this->device[] = $device;
            $device->setDonnee($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->device->removeElement($device)) {
            // set the owning side to null (unless already changed)
            if ($device->getDonnee() === $this) {
                $device->setDonnee(null);
            }
        }

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

}
