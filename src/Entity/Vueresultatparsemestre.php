<?php

namespace App\Entity;

use App\Repository\VueresultatparsemestreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VueresultatparsemestreRepository::class)]
class Vueresultatparsemestre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $semestre = null;

    #[ORM\Column]
    private ?float $moyennegenerale = null;

    #[ORM\Column]
    private ?int $numetudiant = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

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

    public function getMoyennegenerale(): ?float
    {
        return $this->moyennegenerale;
    }

    public function setMoyennegenerale(float $moyennegenerale): static
    {
        $this->moyennegenerale = $moyennegenerale;

        return $this;
    }

    public function getNumetudiant(): ?int
    {
        return $this->numetudiant;
    }

    public function setNumetudiant(int $numetudiant): static
    {
        $this->numetudiant = $numetudiant;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }
}
