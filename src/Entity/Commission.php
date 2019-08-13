<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CommissionRepository")
 */
class Commission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $borneInferieure;

    /**
     * @ORM\Column(type="float")
     */
    private $borneSuperieure;

    /**
     * @ORM\Column(type="float")
     */
    private $valeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorneInferieure(): ?float
    {
        return $this->borneInferieure;
    }

    public function setBorneInferieure(float $borneInferieure): self
    {
        $this->borneInferieure = $borneInferieure;

        return $this;
    }

    public function getBorneSuperieure(): ?float
    {
        return $this->borneSuperieure;
    }

    public function setBorneSuperieure(float $borneSuperieure): self
    {
        $this->borneSuperieure = $borneSuperieure;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }
}
