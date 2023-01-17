<?php

namespace App\Entity;

use App\Repository\QdRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QdRepository::class)]
class Qd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
