<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role")
     */
    private $role;

    public function __construct()
    {
        $this->role = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(User $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
            $role->setRole($this);
        }

        return $this;
    }

    public function removeRole(User $role): self
    {
        if ($this->role->contains($role)) {
            $this->role->removeElement($role);
            // set the owning side to null (unless already changed)
            if ($role->getRole() === $this) {
                $role->setRole(null);
            }
        }

        return $this;
    }
}
