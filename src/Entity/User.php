<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Assert\NotBlank()
     */
    private $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Jouer", mappedBy="user")
     */
    private $jouers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPartie = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail = "toto@toto.to";

    /**
     * @ORM\Column(type="integer")
     */
    private $argentTotal = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bio = "Je suis newbie sur MegaOctet, donc un peu d'aide serait la bienvenue &hearts;";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel = '012345678';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(type="integer")
     */
    private $victoires = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passions;




    public function __construct()
    {
        $this->jouers = new ArrayCollection();
        $this->parties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $jouer->setUser($this);
        }

        return $this;
    }

    public function removeJouer(Jouer $jouer): self
    {
        if ($this->jouers->contains($jouer)) {
            $this->jouers->removeElement($jouer);
            // set the owning side to null (unless already changed)
            if ($jouer->getUser() === $this) {
                $jouer->setUser(null);
            }
        }

        return $this;
    }
    
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getNbPartie(): ?int
    {
        return $this->nbPartie;
    }

    public function setNbPartie(int $nbPartie): self
    {
        $this->nbPartie = $nbPartie;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getArgentTotal(): ?int
    {
        return $this->argentTotal;
    }

    public function setArgentTotal(int $argentTotal): self
    {
        $this->argentTotal = $argentTotal;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getVictoires(): ?int
    {
        return $this->victoires;
    }

    public function setVictoires(int $victoires): self
    {
        $this->victoires = $victoires;

        return $this;
    }

    public function getPassions(): ?string
    {
        return $this->passions;
    }

    public function setPassions(?string $passions): self
    {
        $this->passions = $passions;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->getUsername();
        // to show the id of the Category in the select
        // return $this->id;
    }



}
