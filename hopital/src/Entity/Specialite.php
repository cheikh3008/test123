<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialiteRepository")
 */
/**
 * @ORM\Entity
 * @UniqueEntity(
 * fields={"nom_specialite"},
 * message="Cette specialité existe déja dans la base données ."
 * )
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
    private $nom_specialite;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medecin", mappedBy="specialite")
     */
    private $medecin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Services", inversedBy="specialite")
     * @Assert\NotNull
     */
    private $services;

    public function __construct()
    {
        $this->medecin = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSpecialite(): ?string
    {
        return $this->nom_specialite;
    }

    public function setNomSpecialite(string $nom_specialite): self
    {
        $this->nom_specialite = $nom_specialite;

        return $this;
    }

    /**
     * @return Collection|Medecin[]
     */
    public function getMedecin(): Collection
    {
        return $this->medecin;
    }

    public function addMedecin(Medecin $medecin): self
    {
        if (!$this->medecin->contains($medecin)) {
            $this->medecin[] = $medecin;
            $medecin->addSpecialite($this);
        }

        return $this;
    }

    public function removeMedecin(Medecin $medecin): self
    {
        if ($this->medecin->contains($medecin)) {
            $this->medecin->removeElement($medecin);
            $medecin->removeSpecialite($this);
        }

        return $this;
    }

    public function getServices(): ?services
    {
        return $this->services;
    }

    public function setServices(?services $services): self
    {
        $this->services = $services;

        return $this;
    }
    public function __toString(){
        return $this->nom_specialite;
    }
}
