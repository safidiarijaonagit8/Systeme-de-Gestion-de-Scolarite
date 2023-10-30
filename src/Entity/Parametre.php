<?php

namespace App\Entity;

use App\Repository\ParametreRepository;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: ParametreRepository::class)]

class Parametre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $semestre = null;

    #[ORM\Column]
    private ?int $montantecolage = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrplacedispo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemestre(): ?int
    {
        return $this->semestre;
    }

    public function setSemestre(int $semestre): static
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getMontantecolage(): ?int
    {
        return $this->montantecolage;
    }

    public function setMontantecolage(int $montantecolage): static
    {
        $this->montantecolage = $montantecolage;

        return $this;
    }

    public function getNbrplacedispo(): ?int
    {
        return $this->nbrplacedispo;
    }

    public function setNbrplacedispo(?int $nbrplacedispo): static
    {
        $this->nbrplacedispo = $nbrplacedispo;

        return $this;
    }
}
