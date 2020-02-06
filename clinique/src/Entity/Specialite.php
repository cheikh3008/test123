<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialiteRepository")
 */
class Specialite
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
    private $nomSpecialite;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medecin", mappedBy="specialite")
     */
    private $medecins;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Services", inversedBy="specialite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $services;

    public function __construct()
    {
        $this->medecins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSpecialite(): ?string
    {
        return $this->nomSpecialite;
    }

    public function setNomSpecialite(string $nomSpecialite): self
    {
        $this->nomSpecialite = $nomSpecialite;

        return $this;
    }

    /**
     * @return Collection|Medecin[]
     */
    public function getMedecins(): Collection
    {
        return $this->medecins;
    }

    public function addMedecin(Medecin $medecin): self
    {
        if (!$this->medecins->contains($medecin)) {
            $this->medecins[] = $medecin;
            $medecin->addSpecialite($this);
        }

        return $this;
    }

    public function removeMedecin(Medecin $medecin): self
    {
        if ($this->medecins->contains($medecin)) {
            $this->medecins->removeElement($medecin);
            $medecin->removeSpecialite($this);
        }

        return $this;
    }

    public function getServices(): ?Services
    {
        return $this->services;
    }

    public function setServices(?Services $services): self
    {
        $this->services = $services;

        return $this;
    }
    public function __toString()
    {
        return $this->nomSpecialite;
    }
}
