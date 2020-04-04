<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 * @UniqueEntity("ninea" , message="ce ninea existe déja.")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"compte"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Veuillez remplir ce champ")
     * @Groups({"compte"})
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Veuillez remplir ce champ")
     * @Groups({"compte"})
     */
    private $rc;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire")
     *  @Assert\NotBlank(message = "Veuillez remplir ce champ")
     * @Groups({"compte"})
     */
    private $userPartenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="partenaire")
     *  @Assert\NotBlank(message = "Veuillez remplir ce champ")
     */
    private $comptes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contrat", cascade={"persist", "remove"})
     */
    private $contrat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="user")
     */
    private $userCreateur;

    public function __construct()
    {
        $this->userPartenaire = new ArrayCollection();
        $this->comptes = new ArrayCollection();
        $this->userCreateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(string $rc): self
    {
        $this->rc = $rc;

        return $this;
    }

    

    /**
     * @return Collection|User[]
     */
    public function getUserPartenaire(): Collection
    {
        return $this->userPartenaire;
    }

    public function addUserPartenaire(User $userPartenaire): self
    {
        if (!$this->userPartenaire->contains($userPartenaire)) {
            $this->userPartenaire[] = $userPartenaire;
            $userPartenaire->setPartenaire($this);
        }

        return $this;
    }

    public function removeUserPartenaire(User $userPartenaire): self
    {
        if ($this->userPartenaire->contains($userPartenaire)) {
            $this->userPartenaire->removeElement($userPartenaire);
            // set the owning side to null (unless already changed)
            if ($userPartenaire->getPartenaire() === $this) {
                $userPartenaire->setPartenaire(null);
            }
        }

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

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserCreateur(): Collection
    {
        return $this->userCreateur;
    }

    public function addUserCreateur(User $userCreateur): self
    {
        if (!$this->userCreateur->contains($userCreateur)) {
            $this->userCreateur[] = $userCreateur;
            $userCreateur->setUser($this);
        }

        return $this;
    }

    public function removeUserCreateur(User $userCreateur): self
    {
        if ($this->userCreateur->contains($userCreateur)) {
            $this->userCreateur->removeElement($userCreateur);
            // set the owning side to null (unless already changed)
            if ($userCreateur->getUser() === $this) {
                $userCreateur->setUser(null);
            }
        }

        return $this;
    }
}
