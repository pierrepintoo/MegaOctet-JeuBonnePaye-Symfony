<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $partie_date_debut;

    /**
     * @ORM\Column(type="integer")
     */
    private $partie_qui_joue;

    /**
     * @ORM\Column(type="integer")
     */
    private $partie_gagnant = 0;

    /**
     * @ORM\Column(type="text")
     */
    private $partie_pioche;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $partie_defausse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partie_cagnotte = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partie_etat = "NC";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $partie_date_fin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Jouer", mappedBy="partie")
     */
    private $jouers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $banque;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qui_joue_loterie;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeRejoindre;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $nbTours;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbToursRestants;

    public function __construct()
    {
        $this->partie_update = new ArrayCollection();
        $this->jouers = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->code_partie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartieDateDebut(): ?\DateTimeInterface
    {
        return $this->partie_date_debut;
    }

    public function setPartieDateDebut(?\DateTimeInterface $partie_date_debut): self
    {
        $this->partie_date_debut = $partie_date_debut;

        return $this;
    }

    public function getPartieQuiJoue(): ?int
    {
        return $this->partie_qui_joue;
    }

    public function setPartieQuiJoue(?int $partie_qui_joue): self
    {
        $this->partie_qui_joue = $partie_qui_joue;

        return $this;
    }

    public function getPartieGagnant(): ?int
    {
        return $this->partie_gagnant;
    }

    public function setPartieGagnant(?int $partie_gagnant): self
    {
        $this->partie_gagnant = $partie_gagnant;

        return $this;
    }

    public function getPartiePioche(): ?array
    {
        return json_decode($this->partie_pioche, true);
    }

    public function setPartiePioche(array $partie_pioche): self
    {
        $this->partie_pioche = json_encode($partie_pioche);

        return $this;
    }

    public function getPartieDefausse(): ?array
    {
        return json_decode($this->partie_defausse, true);
    }

    public function setPartieDefausse(?array $partie_defausse): self
    {
        $this->partie_defausse = json_encode($partie_defausse);

        return $this;
    }

    public function getPartieCagnotte(): ?int
    {
        return $this->partie_cagnotte;
    }

    public function setPartieCagnotte(?int $partie_cagnotte): self
    {
        $this->partie_cagnotte = $partie_cagnotte;

        return $this;
    }

    public function getPartieEtat(): ?string
    {
        return $this->partie_etat;
    }

    public function setPartieEtat(?string $partie_etat): self
    {
        $this->partie_etat = $partie_etat;

        return $this;
    }

    public function getPartieDateFin(): ?\DateTimeInterface
    {
        return $this->partie_date_fin;
    }

    public function setPartieDateFin(?\DateTimeInterface $partie_date_fin): self
    {
        $this->partie_date_fin = $partie_date_fin;

        return $this;
    }

    /**
     * @return Collection|Jouer[]
     */
    public function getJouers(): Collection
    {
        return $this->jouers;
    }

    public function addJouer(Jouer $jouer): self
    {
        if (!$this->jouers->contains($jouer)) {
            $this->jouers[] = $jouer;
            $jouer->setPartie($this);
        }

        return $this;
    }

    public function removeJouer(Jouer $jouer): self
    {
        if ($this->jouers->contains($jouer)) {
            $this->jouers->removeElement($jouer);
            // set the owning side to null (unless already changed)
            if ($jouer->getPartie() === $this) {
                $jouer->setPartie(null);
            }
        }

        return $this;
    }

    public function getBanque(): ?int
    {
        return $this->banque;
    }

    public function setBanque(?int $banque): self
    {
        $this->banque = $banque;

        return $this;
    }

    public function getQuiJoueLoterie(): ?int
    {
        return $this->qui_joue_loterie;
    }

    public function setQuiJoueLoterie(?int $qui_joue_loterie): self
    {
        $this->qui_joue_loterie = $qui_joue_loterie;

        return $this;
    }

    public function getCodeRejoindre(): ?int
    {
        return $this->codeRejoindre;
    }

    public function setCodeRejoindre(int $codeRejoindre): self
    {
        $this->codeRejoindre = $codeRejoindre;

        return $this;
    }

    public function getNbTours(): ?int
    {
        return $this->nbTours;
    }

    public function setNbTours(int $nbTours): self
    {
        $this->nbTours = $nbTours;

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
        return $this->getPartieEtat();
        // to show the id of the Category in the select
        // return $this->id;
    }

}
