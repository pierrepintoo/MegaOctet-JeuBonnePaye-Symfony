<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoxRepository")
 */
class Box
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $box_position;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $box_heure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $box_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $effet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoxPosition(): ?int
    {
        return $this->box_position;
    }

    public function setBoxPosition(?int $box_position): self
    {
        $this->box_position = $box_position;

        return $this;
    }

    public function getBoxHeure(): ?string
    {
        return $this->box_heure;
    }

    public function setBoxHeure(?string $box_heure): self
    {
        $this->box_heure = $box_heure;

        return $this;
    }

    public function getBoxImage(): ?string
    {
        return $this->box_image;
    }

    public function setBoxImage(string $box_image): self
    {
        $this->box_image = $box_image;

        return $this;
    }

    public function getEffet(): ?string
    {
        return $this->effet;
    }

    public function setEffet(string $effet): self
    {
        $this->effet = $effet;

        return $this;
    }
}
