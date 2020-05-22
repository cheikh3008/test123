<?php
namespace App\Entity;


class RecherchePart
{
    
    private $dateDebut;
   
    private $dateFin;

    private $ninea;

    public function getDateDebut():  ?\DateTimeInterface
    {
        return $this->dateDebut;
    }
   
    public function setDateDebut(\DateTimeInterface $dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }
   
    public function getDateFin():  ?\DateTimeInterface
    {
        return $this->dateFin;
    }
    
    public function setDateFin(\DateTimeInterface $dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }
    public function getNinea(): ?string
    {
        return $this->ninea ;
    }
    public function setNinea(string $ninea) 
    {
        $this->ninea = $ninea;
        return $this;
    }
    
}