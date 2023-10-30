<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $semestre = null;

    #[ORM\Column]
    private ?int $idetudiant = null;

    #[ORM\Column]
    private ?int $montantpaye = null;

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

    public function getIdetudiant(): ?int
    {
        return $this->idetudiant;
    }

    public function setIdetudiant(int $idetudiant): static
    {
        $this->idetudiant = $idetudiant;

        return $this;
    }

    public function getMontantpaye(): ?int
    {
        return $this->montantpaye;
    }

    public function setMontantpaye(int $montantpaye): static
    {
        $this->montantpaye = $montantpaye;

        return $this;
    }
}
