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

    #[ORM\ManyToMany(targetEntity: Score::class, mappedBy: 'supplements')]
    private Collection $scores;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\ManyToMany(targetEntity: Answer::class, mappedBy: 'supplements')]
    private Collection $answers;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->answers = new ArrayCollection();
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

    public function getType(): ?SupplementType
    {
        return $this->type;
    }

    public function setType(?SupplementType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->addSupplement($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            $score->removeSupplement($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->addSupplement($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            $answer->removeSupplement($this);
        }

        return $this;
    }
}
