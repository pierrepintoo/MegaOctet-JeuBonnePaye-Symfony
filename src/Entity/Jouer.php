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
    private $partie_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="jouers")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $classement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $argent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cartes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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

    public function getPartieId(): ?Partie
    {
        return $this->partie_id;
    }

    public function setPartieId(?Partie $partie_id): self
    {
        $this->partie_id = $partie_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getClassement(): ?int
    {
        return $this->classement;
    }

    public function setClassement(?int $classement): self
    {
        $this->classement = $classement;

        return $this;
    }

    public function getArgent(): ?int
    {
        return $this->argent;
    }

    public function setArgent(?int $argent): self
    {
        $this->argent = $argent;

        return $this;
    }

    public function getCartes(): ?string
    {
        return $this->cartes;
    }

    public function setCartes(?string $cartes): self
    {
        $this->cartes = $cartes;

        return $this;
    }

    public function getPion(): ?string
    {
        return $this->pion;
    }

    public function setPion(?string $pion): self
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
