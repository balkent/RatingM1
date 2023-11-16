<?php

namespace App\Entity;

use App\Entity\Score;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(options: ["default" => 20])]
    private int $maximumRating = 20;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: Score::class, orphanRemoval: true)]
    private Collection $scores;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: Exercise::class)]
    private Collection $exercise;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->exercise = new ArrayCollection();
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

    public function getMaximumRating(): int
    {
        return $this->maximumRating;
    }

    public function setMaximumRating(int $maximumRating): static
    {
        $this->maximumRating = $maximumRating;

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
            $score->setSubject($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getSubject() === $this) {
                $score->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exercise>
     */
    public function getExercise(): Collection
    {
        return $this->exercise;
    }

    public function addExercise(Exercise $exercise): static
    {
        if (!$this->exercise->contains($exercise)) {
            $this->exercise->add($exercise);
            $exercise->setSubject($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): static
    {
        if ($this->exercise->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getSubject() === $this) {
                $exercise->setSubject(null);
            }
        }

        return $this;
    }

    public function withSearch(?string $value): Collection
    {
        return $this->scores->filter(function(Score $score) use ($value) {
            if (null === $value) {
                return true;
            }

            return false !== strpos(strtolower($score->getStudent()->getName()), strtolower($value))
                or false !== strpos(strtolower($score->getStudent()->getLastName()), strtolower($value))
                or false !== strpos(strtolower($score->getStudent()->getEmail()), strtolower($value))
            ;
        });
    }
}
