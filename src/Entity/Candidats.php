<?php

namespace App\Entity;

use App\Repository\CandidatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatsRepository::class)]
class Candidats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedenaissance = null;

    #[ORM\Column]
    private ?float $moyennebacc = null;

    #[ORM\Column(length: 50)]
    private ?string $estdejaadmis = null;

    #[ORM\Column(length: 255)]
    private ?string $imagefichiername = null;

   


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatedenaissance(): ?\DateTimeInterface
    {
        return $this->datedenaissance;
    }

    public function setDatedenaissance(\DateTimeInterface $datedenaissance): static
    {
        $this->datedenaissance = $datedenaissance;

        return $this;
    }

    public function getMoyennebacc(): ?float
    {
        return $this->moyennebacc;
    }

    public function setMoyennebacc(float $moyennebacc): static
    {
        $this->moyennebacc = $moyennebacc;

        return $this;
    }

    public function getEstdejaadmis(): ?string
    {
        return $this->estdejaadmis;
    }

    public function setEstdejaadmis(string $estdejaadmis): static
    {
        $this->estdejaadmis = $estdejaadmis;

        return $this;
    }

    public function getImagefichiername(): ?string
    {
        return $this->imagefichiername;
    }

    public function setImagefichiername(string $imagefichiername): static
    {
        $this->imagefichiername = $imagefichiername;

        return $this;
    }

   
}
