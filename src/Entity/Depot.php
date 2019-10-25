<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"depot"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"depot"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="float")
     * @Groups({"depot"})
     */
    private $montantDepot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"depot"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"depot"})
     */
    private $caissier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getMontantDepot(): ?float
    {
        return $this->montantDepot;
    }

    public function setMontantDepot(float $montantDepot): self
    {
        $this->montantDepot = $montantDepot;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getCaissier(): ?Utilisateur
    {
        return $this->caissier;
    }

    public function setCaissier(?Utilisateur $caissier): self
    {
        $this->caissier = $caissier;

        return $this;
    }
}
