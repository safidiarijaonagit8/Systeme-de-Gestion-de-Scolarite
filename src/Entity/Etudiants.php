<?php

namespace App\Entity;

use App\Repository\EtudiantsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantsRepository::class)]
class Etudiants
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  
    #[ORM\Column]
    private ?int $idcandidat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    

   

    public function getIdcandidat(): ?int
    {
        return $this->idcandidat;
    }

    public function setIdcandidat(int $idcandidat): static
    {
        $this->idcandidat = $idcandidat;

        return $this;
    }
}
