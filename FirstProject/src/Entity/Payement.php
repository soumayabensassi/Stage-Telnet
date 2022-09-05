<?php

namespace App\Entity;

use App\Repository\PayementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PayementRepository::class)
 */
class Payement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le nom est obligatoire")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = "16",
     *      max = "16",
     *      minMessage = "Votre numero de la carte doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre numero de la carte ne peut pas être plus long que {{ limit }} caractères")
     */
    private $numcarte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = "3",
     *      max = "3",
     *      minMessage = "CVV2 doit faire au moins {{ limit }} caractères",
     *      maxMessage = "CVV2 ne peut pas être plus long que {{ limit }} caractères")
     */
    private $cvv2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le nom est obligatoire")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le nom est obligatoire")
     */
    private $expire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $test;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getNumcarte(): ?string
    {
        return $this->numcarte;
    }

    public function setNumcarte(string $numcarte): self
    {
        $this->numcarte = $numcarte;

        return $this;
    }

    public function getCvv2(): ?string
    {
        return $this->cvv2;
    }

    public function setCvv2(string $cvv2): self
    {
        $this->cvv2 = $cvv2;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getExpire(): ?string
    {
        return $this->expire;
    }

    public function setExpire(string $expire): self
    {
        $this->expire = $expire;

        return $this;
    }

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(?string $test): self
    {
        $this->test = $test;

        return $this;
    }
}
