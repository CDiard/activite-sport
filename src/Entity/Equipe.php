<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'Equipe', targetEntity: Joueur::class)]
    private Collection $idJoueur;

    public function __construct()
    {
        $this->idJoueur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Joueur>
     */
    public function getIdJoueur(): Collection
    {
        return $this->idJoueur;
    }

    public function addIdJoueur(Joueur $idJoueur): self
    {
        if (!$this->idJoueur->contains($idJoueur)) {
            $this->idJoueur->add($idJoueur);
            $idJoueur->setEquipe($this);
        }

        return $this;
    }

    public function removeIdJoueur(Joueur $idJoueur): self
    {
        if ($this->idJoueur->removeElement($idJoueur)) {
            // set the owning side to null (unless already changed)
            if ($idJoueur->getEquipe() === $this) {
                $idJoueur->setEquipe(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->getNom();
    }
}
