<?php

namespace App\Entity;

use App\Repository\MoyenneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoyenneRepository::class)]
class Moyenne
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
    private ?float $moyennegenerale = null;

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

    public function getMoyennegenerale(): ?float
    {
        return $this->moyennegenerale;
    }

    public function setMoyennegenerale(float $moyennegenerale): static
    {
        $this->moyennegenerale = $moyennegenerale;

        return $this;
    }
}
