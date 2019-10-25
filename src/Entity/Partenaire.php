<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 * @UniqueEntity(
 *     fields={"ninea"},
 *     message="Cet Utilisateur ou le numéro de Téléphone ou l'email existe déja."
 * )
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"partenaire","compte"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", unique=true)
     * @Groups({"partenaire", "compte", "utilisateur", "depot"})
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partenaire"})
     */
    private $localisation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partenaire"})
     */
    private $domaineActivite;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"partenaire"})
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="Partenaire")
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="partenaire")
     */
    private $utilisateurs;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"partenaire"})
     */
    private $statut;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?float
    {
        return $this->ninea;
    }

    public function setNinea(float $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDomaineActivite(): ?string
    {
        return $this->domaineActivite;
    }

    public function setDomaineActivite(string $domaineActivite): self
    {
        $this->domaineActivite = $domaineActivite;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setPartenaire($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getPartenaire() === $this) {
                $compte->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setPartenaire($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->removeElement($utilisateur);
            // set the owning side to null (unless already changed)
            if ($utilisateur->getPartenaire() === $this) {
                $utilisateur->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
