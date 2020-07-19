<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JouerRepository")
 */
class Jouer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partie", inversedBy="jouers")
     */
    private $partie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="jouers")
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $classement;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $argent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $box = 6;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $de;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tour;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $argent_en_attente;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tp_rendu = false;


    /**
     * @ORM\Column(type="boolean")
     */
    private $de_lance;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $Cartes = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $qui_mise_loterie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $de_miser_loterie;

    /**
     * @ORM\Column(type="boolean")
     */
    private $jai_pioche;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbToursRestants;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartie(): ?Partie
    {
        return $this->partie;
    }

    public function setPartie($partie): self
    {
        $this->partie = $partie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getClassement(): ?int
    {
        return $this->classement;
    }

    public function setClassement(int $classement): self
    {
        $this->classement = $classement;

        return $this;
    }

    public function getArgent(): ?float
    {
        return $this->argent;
    }

    public function setArgent(?float $argent): self
    {
        $this->argent = $argent;

        return $this;
    }

    public function getPion(): ?int
    {
        return $this->pion;
    }

    public function setPion(?int $pion): self
    {
        $this->pion = $pion;

        return $this;
    }

    public function getBox(): ?int
    {
        return $this->box;
    }

    public function setBox(?int $box): self
    {
        $this->box = $box;

        return $this;
    }

    public function getDe(): ?int
    {
        return $this->de;
    }

    public function setDe(?int $de): self
    {
        $this->de = $de;

        return $this;
    }

    public function getTour(): ?int
    {
        return $this->tour;
    }

    public function setTour(?int $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getArgentEnAttente(): ?int
    {
        return $this->argent_en_attente;
    }

    public function setArgentEnAttente(?int $argent_en_attente): self
    {
        $this->argent_en_attente = $argent_en_attente;

        return $this;
    }

    public function getTpRendu(): ?bool
    {
        return $this->tp_rendu;
    }

    public function setTpRendu(bool $tp_rendu): self
    {
        $this->tp_rendu = $tp_rendu;

        return $this;
    }

    public function getDeLance(): ?bool
    {
        return $this->de_lance;
    }

    public function setDeLance(bool $de_lance): self
    {
        $this->de_lance = $de_lance;

        return $this;
    }

    public function getCartes(): ?array
    {
        return $this->Cartes;
    }

    public function setCartes(?array $Cartes): self
    {
        $this->Cartes = $Cartes;

        return $this;
    }

    public function getQuiMiseLoterie(): ?bool
    {
        return $this->qui_mise_loterie;
    }

    public function setQuiMiseLoterie(bool $qui_mise_loterie): self
    {
        $this->qui_mise_loterie = $qui_mise_loterie;

        return $this;
    }

    public function getDeMiserLoterie(): ?int
    {
        return $this->de_miser_loterie;
    }

    public function setDeMiserLoterie(?int $de_miser_loterie): self
    {
        $this->de_miser_loterie = $de_miser_loterie;

        return $this;
    }

    public function getJaiPioche(): ?bool
    {
        return $this->jai_pioche;
    }

    public function setJaiPioche(bool $jai_pioche): self
    {
        $this->jai_pioche = $jai_pioche;

        return $this;
    }

    public function getNbToursRestants(): ?int
    {
        return $this->nbToursRestants;
    }

    public function setNbToursRestants(int $nbToursRestants): self
    {
        $this->nbToursRestants = $nbToursRestants;

        return $this;
    }
    public function __toString(){
        // to show the name of the Category in the select
        return $this->getUser()->getUsername();
        // to show the id of the Category in the select
        // return $this->id;
    }





}
