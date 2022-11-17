<?php

namespace App\Entity;
use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (unique: true)]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: false)]
    private ?string $username = null;


    #[ORM\ManyToOne(inversedBy: 'idJoueur')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipe $Equipe=null;
    //private ?Equipe $Equipe;
    // Probleme : PropertyAccessor requires a graph of objects or arrays to operate on, but it found type "NULL" while trying to traverse path "equipe.nom" at property "nom".

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


     // A visual identifier that represents this user.

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }



    public function getEquipe(): ?Equipe
    {
        return $this->Equipe;
    }


    public function setEquipe(?Equipe $Equipe): self
    {
        $this->Equipe = $Equipe;

        return $this;
    }



}
