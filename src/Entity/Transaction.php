<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @UniqueEntity(
 *     fields={"numeroTransaction"},
 *     message="Ce Numéro de Transaction existe déja existe déja."
 * )
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomEnvoyeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomEnvoyeur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseEnvoyeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telEnvoyeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CNIEnvoyeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseBeneficiaire;

    /**
     * @ORM\Column(type="float", unique=true)
     */
    private $numeroTransaction;

    /**
     * @ORM\Column(type="float")
     */
    private $montantEnvoyer;

    /**
     * @ORM\Column(type="float")
     */
    private $totalEnvoyer;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montantRetirer;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $CNIBeneficiaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnvoie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commission", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commissionTTC;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEnvoyeur(): ?string
    {
        return $this->nomEnvoyeur;
    }

    public function setNomEnvoyeur(string $nomEnvoyeur): self
    {
        $this->nomEnvoyeur = $nomEnvoyeur;

        return $this;
    }

    public function getPrenomEnvoyeur(): ?string
    {
        return $this->prenomEnvoyeur;
    }

    public function setPrenomEnvoyeur(string $prenomEnvoyeur): self
    {
        $this->prenomEnvoyeur = $prenomEnvoyeur;

        return $this;
    }

    public function getAdresseEnvoyeur(): ?string
    {
        return $this->adresseEnvoyeur;
    }

    public function setAdresseEnvoyeur(?string $adresseEnvoyeur): self
    {
        $this->adresseEnvoyeur = $adresseEnvoyeur;

        return $this;
    }

    public function getTelEnvoyeur(): ?string
    {
        return $this->telEnvoyeur;
    }

    public function setTelEnvoyeur(string $telEnvoyeur): self
    {
        $this->telEnvoyeur = $telEnvoyeur;

        return $this;
    }

    public function getCNIEnvoyeur(): ?string
    {
        return $this->CNIEnvoyeur;
    }

    public function setCNIEnvoyeur(string $CNIEnvoyeur): self
    {
        $this->CNIEnvoyeur = $CNIEnvoyeur;

        return $this;
    }

    public function getNomBeneficiaire(): ?string
    {
        return $this->nomBeneficiaire;
    }

    public function setNomBeneficiaire(string $nomBeneficiaire): self
    {
        $this->nomBeneficiaire = $nomBeneficiaire;

        return $this;
    }

    public function getPrenomBeneficiaire(): ?string
    {
        return $this->prenomBeneficiaire;
    }

    public function setPrenomBeneficiaire(string $prenomBeneficiaire): self
    {
        $this->prenomBeneficiaire = $prenomBeneficiaire;

        return $this;
    }

    public function getTelBeneficiaire(): ?string
    {
        return $this->telBeneficiaire;
    }

    public function setTelBeneficiaire(string $telBeneficiaire): self
    {
        $this->telBeneficiaire = $telBeneficiaire;

        return $this;
    }

    public function getAdresseBeneficiaire(): ?string
    {
        return $this->adresseBeneficiaire;
    }

    public function setAdresseBeneficiaire(?string $adresseBeneficiaire): self
    {
        $this->adresseBeneficiaire = $adresseBeneficiaire;

        return $this;
    }

    public function getNumeroTransaction(): ?float
    {
        return $this->numeroTransaction;
    }

    public function setNumeroTransaction(float $numeroTransaction): self
    {
        $this->numeroTransaction = $numeroTransaction;

        return $this;
    }

    public function getMontantEnvoyer(): ?float
    {
        return $this->montantEnvoyer;
    }

    public function setMontantEnvoyer(float $montantEnvoyer): self
    {
        $this->montantEnvoyer = $montantEnvoyer;

        return $this;
    }

    public function getTotalEnvoyer(): ?float
    {
        return $this->totalEnvoyer;
    }

    public function setTotalEnvoyer(float $totalEnvoyer): self
    {
        $this->totalEnvoyer = $totalEnvoyer;

        return $this;
    }

    public function getMontantRetirer(): ?float
    {
        return $this->montantRetirer;
    }

    public function setMontantRetirer(?float $montantRetirer): self
    {
        $this->montantRetirer = $montantRetirer;

        return $this;
    }

    public function getCNIBeneficiaire(): ?float
    {
        return $this->CNIBeneficiaire;
    }

    public function setCNIBeneficiaire(?float $CNIBeneficiaire): self
    {
        $this->CNIBeneficiaire = $CNIBeneficiaire;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(\DateTimeInterface $dateEnvoie): self
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(?\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getCommissionTTC(): ?Commission
    {
        return $this->commissionTTC;
    }

    public function setCommissionTTC(?Commission $commissionTTC): self
    {
        $this->commissionTTC = $commissionTTC;

        return $this;
    }
}
