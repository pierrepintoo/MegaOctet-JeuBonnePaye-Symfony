<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoueurRepository")
 */
class Joueur
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
    private $joueur_pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $joueur_mdp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $joueur_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $joueur_prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $joueur_nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $joueur_adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $joueur_type;

    /**
     * @ORM\OneToMany(targetEntity="Jouers", mappedBy="joueur_id")
     */
    private $joueur_update;

    public function __construct()
    {
        $this->joueur_update = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueurPseudo(): ?string
    {
        return $this->joueur_pseudo;
    }

    public function setJoueurPseudo(string $joueur_pseudo): self
    {
        $this->joueur_pseudo = $joueur_pseudo;

        return $this;
    }

    public function getJoueurMdp(): ?string
    {
        return $this->joueur_mdp;
    }

    public function setJoueurMdp(string $joueur_mdp): self
    {
        $this->joueur_mdp = $joueur_mdp;

        return $this;
    }

    public function getJoueurEmail(): ?string
    {
        return $this->joueur_email;
    }

    public function setJoueurEmail(string $joueur_email): self
    {
        $this->joueur_email = $joueur_email;

        return $this;
    }

    public function getJoueurPrenom(): ?string
    {
        return $this->joueur_prenom;
    }

    public function setJoueurPrenom(string $joueur_prenom): self
    {
        $this->joueur_prenom = $joueur_prenom;

        return $this;
    }

    public function getJoueurNom(): ?string
    {
        return $this->joueur_nom;
    }

    public function setJoueurNom(string $joueur_nom): self
    {
        $this->joueur_nom = $joueur_nom;

        return $this;
    }

    public function getJoueurAdresse(): ?string
    {
        return $this->joueur_adresse;
    }

    public function setJoueurAdresse(?string $joueur_adresse): self
    {
        $this->joueur_adresse = $joueur_adresse;

        return $this;
    }

    public function getJoueurType(): ?string
    {
        return $this->joueur_type;
    }

    public function setJoueurType(?string $joueur_type): self
    {
        $this->joueur_type = $joueur_type;

        return $this;
    }

    /**
     * @return Collection|Jouers[]
     */
    public function getJoueurUpdate(): Collection
    {
        return $this->joueur_update;
    }

    public function addJoueurUpdate(Jouers $joueurUpdate): self
    {
        if (!$this->joueur_update->contains($joueurUpdate)) {
            $this->joueur_update[] = $joueurUpdate;
            $joueurUpdate->setJoueurId($this);
        }

        return $this;
    }

    public function removeJoueurUpdate(Jouers $joueurUpdate): self
    {
        if ($this->joueur_update->contains($joueurUpdate)) {
            $this->joueur_update->removeElement($joueurUpdate);
            // set the owning side to null (unless already changed)
            if ($joueurUpdate->getJoueurId() === $this) {
                $joueurUpdate->setJoueurId(null);
            }
        }

        return $this;
    }
}
