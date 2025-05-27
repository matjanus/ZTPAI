<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['quiz:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups(['quiz:read'])]
    private ?string $quizName = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Access $access = null;

    /**
     * @var Collection<int, QuizVocabulary>
     */
    #[ORM\OneToMany(targetEntity: QuizVocabulary::class, mappedBy: 'quiz', orphanRemoval: true)]
    private Collection $quizVocabularies;

    /**
     * @var Collection<int, UserRating>
     */
    #[ORM\OneToMany(targetEntity: UserRating::class, mappedBy: 'quiz', orphanRemoval: true)]
    private Collection $userRatings;

    public function __construct()
    {
        $this->quizVocabularies = new ArrayCollection();
        $this->userRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuizName(): ?string
    {
        return $this->quizName;
    }

    public function setQuizName(string $quizName): static
    {
        $this->quizName = $quizName;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAccess(): ?Access
    {
        return $this->access;
    }

    public function setAccess(?Access $access): static
    {
        $this->access = $access;

        return $this;
    }

    /**
     * @return Collection<int, QuizVocabulary>
     */
    public function getQuizVocabularies(): Collection
    {
        return $this->quizVocabularies;
    }

    public function addQuizVocabulary(QuizVocabulary $quizVocabulary): static
    {
        if (!$this->quizVocabularies->contains($quizVocabulary)) {
            $this->quizVocabularies->add($quizVocabulary);
            $quizVocabulary->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizVocabulary(QuizVocabulary $quizVocabulary): static
    {
        if ($this->quizVocabularies->removeElement($quizVocabulary)) {
            // set the owning side to null (unless already changed)
            if ($quizVocabulary->getQuiz() === $this) {
                $quizVocabulary->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserRating>
     */
    public function getUserRatings(): Collection
    {
        return $this->userRatings;
    }

    public function addUserRating(UserRating $userRating): static
    {
        if (!$this->userRatings->contains($userRating)) {
            $this->userRatings->add($userRating);
            $userRating->setQuiz($this);
        }

        return $this;
    }

    public function removeUserRating(UserRating $userRating): static
    {
        if ($this->userRatings->removeElement($userRating)) {
            // set the owning side to null (unless already changed)
            if ($userRating->getQuiz() === $this) {
                $userRating->setQuiz(null);
            }
        }

        return $this;
    }
}
