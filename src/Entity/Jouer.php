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
     * @ORM\Column(type="integer")
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
    private $box;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $de;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tour;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartie(): ?Partie
    {
        return $this->partie;
    }

    public function setPartie(?Partie $partie): self
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
}
