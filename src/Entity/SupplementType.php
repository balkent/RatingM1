<?php

namespace App\Entity;

use App\Entity\Supplement;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Dictionary\SupplementTypeDictionary;
use App\Repository\SupplementTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SupplementTypeRepository::class)]
class SupplementType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Supplement::class, orphanRemoval: true)]
    private Collection $supplements;

    public function __construct()
    {
        $this->supplements = new ArrayCollection();
    }

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

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getDisplay(): string
    {
        return '('.$this->rating.') '.SupplementTypeDictionary::ALL[$this->libelle];
    }

    /**
     * @return Collection<int, Supplement>
     */
    public function getSupplements(): Collection
    {
        return $this->supplements;
    }

    public function addSupplement(Supplement $supplement): static
    {
        if (!$this->supplements->contains($supplement)) {
            $this->supplements->add($supplement);
            $supplement->setType($this);
        }

        return $this;
    }

    public function removeSupplement(Supplement $supplement): static
    {
        if ($this->supplements->removeElement($supplement)) {
            // set the owning side to null (unless already changed)
            if ($supplement->getType() === $this) {
                $supplement->setType(null);
            }
        }

        return $this;
    }
}
