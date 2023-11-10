<?php

namespace App\Entity;

use App\Repository\SupplementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplementRepository::class)]
class Supplement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'supplements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SupplementType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getType(): ?SupplementType
    {
        return $this->type;
    }

    public function setType(?SupplementType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
