<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="commissionTTC")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

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

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCommissionTTC($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCommissionTTC() === $this) {
                $transaction->setCommissionTTC(null);
            }
        }

        return $this;
    }
}
