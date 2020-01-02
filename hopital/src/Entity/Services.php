<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicesRepository")
 */
/**
 * @ORM\Entity
 * @UniqueEntity(
 * fields={"nom_service"},
 * message="Ce service existe déja dans la base données ."
 * )
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
    private $nom_service;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Medecin", mappedBy="services")
     */
    private $medecin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Specialite", mappedBy="services")
     */
    private $specialite;

    public function __construct()
    {
        $this->medecin = new ArrayCollection();
        $this->specialite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        
        return $this->nom_service;
    }

    public function setNomService(string $nom_service): self
    {
        $this->nom_service = $nom_service;

        return $this;
    }

    /**
     * @return Collection|medecin[]
     */
    public function getMedecin(): Collection
    {
        return $this->medecin;
    }

    public function addMedecin(medecin $medecin): self
    {
        if (!$this->medecin->contains($medecin)) {
            $this->medecin[] = $medecin;
            $medecin->setServices($this);
        }

        return $this;
    }

    public function removeMedecin(medecin $medecin): self
    {
        if ($this->medecin->contains($medecin)) {
            $this->medecin->removeElement($medecin);
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
    public function __toString(){
        return $this->nom_service;
    }
}
