<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicesRepository")
 */
class Services
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
    private $nomService;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Medecin", mappedBy="services")
     */
    private $medecins;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Specialite", mappedBy="services")
     */
    private $specialite;

    public function __construct()
    {
        $this->medecins = new ArrayCollection();
        $this->specialite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->nomService;
    }

    public function setNomService(string $nomService): self
    {
        $this->nomService = $nomService;

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
            $medecin->setServices($this);
        }

        return $this;
    }

    public function removeMedecin(Medecin $medecin): self
    {
        if ($this->medecins->contains($medecin)) {
            $this->medecins->removeElement($medecin);
            // set the owning side to null (unless already changed)
            if ($medecin->getServices() === $this) {
                $medecin->setServices(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Specialite[]
     */
    public function getSpecialite(): Collection
    {
        return $this->specialite;
    }

    public function addSpecialite(Specialite $specialite): self
    {
        if (!$this->specialite->contains($specialite)) {
            $this->specialite[] = $specialite;
            $specialite->setServices($this);
        }

        return $this;
    }

    public function removeSpecialite(Specialite $specialite): self
    {
        if ($this->specialite->contains($specialite)) {
            $this->specialite->removeElement($specialite);
            // set the owning side to null (unless already changed)
            if ($specialite->getServices() === $this) {
                $specialite->setServices(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nomService;
    }
}
