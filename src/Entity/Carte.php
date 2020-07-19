<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarteRepository")
 */
class Carte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carte_nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carte_image_recto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carte_image_verso;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carte_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carte_effet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $carte_montant;

    /**
     * @ORM\Column(type="integer")
     */
    private $gain_mise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarteNom(): ?string
    {
        return $this->carte_nom;
    }

    public function setCarteNom(?string $carte_nom): self
    {
        $this->carte_nom = $carte_nom;

        return $this;
    }

    public function getCarteImageRecto(): ?string
    {
        return $this->carte_image_recto;
    }

    public function setCarteImageRecto(?string $carte_image_recto): self
    {
        $this->carte_image_recto = $carte_image_recto;

        return $this;
    }

    public function getCarteImageVerso(): ?string
    {
        return $this->carte_image_verso;
    }

    public function setCarteImageVerso(?string $carte_image_verso): self
    {
        $this->carte_image_verso = $carte_image_verso;

        return $this;
    }

    public function getCarteType(): ?string
    {
        return $this->carte_type;
    }

    public function setCarteType(?string $carte_type): self
    {
        $this->carte_type = $carte_type;

        return $this;
    }

    public function getCarteEffet(): ?string
    {
        return $this->carte_effet;
    }

    public function setCarteEffet(?string $carte_effet): self
    {
        $this->carte_effet = $carte_effet;

        return $this;
    }

    public function getCarteMontant(): ?int
    {
        return $this->carte_montant;
    }

    public function setCarteMontant(?int $carte_montant): self
    {
        $this->carte_montant = $carte_montant;

        return $this;
    }

    public function getGainMise(): ?int
    {
        return $this->gain_mise;
    }

    public function setGainMise(int $gain_mise): self
    {
        $this->gain_mise = $gain_mise;

        return $this;
    }

    public function getJson()
    {
        //on pourrait le faire avec un sérializer... mais pas l'objet de ce module
        $t['id'] = $this->getId();
        $t['nom'] = $this->getCarteNom();
        $t['effet'] = $this->getCarteEffet();
        $t['image_recto'] = $this->getCarteImageRecto();
        $t['image_verso'] = $this->getCarteImageVerso();
        $t['montant'] = $this->getCarteMontant();
        $t['type'] = $this->getCarteType();
        $t['gain'] = $this->getGainMise();

        return $t;
    }
}
