<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\Index(name: 'idx_username', columns: ['username'])]
#[ORM\Index(name: 'idx_email', columns: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\Column(length: 80)]
    private ?string $password = null;

    #[ORM\Column(length: 40)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'idRole')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;


    /**
     * @var Collection<int, Quiz>
     */
    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $quizzes;

    /**
     * @var Collection<int, UserPlay>
     */
    #[ORM\OneToMany(targetEntity: UserPlay::class, mappedBy: 'player', orphanRemoval: true)]
    private Collection $userPlays;

    /**
     * @var Collection<int, FavouriteQuiz>
     */
    #[ORM\OneToMany(targetEntity: FavouriteQuiz::class, mappedBy: 'Patron', orphanRemoval: true)]
    private Collection $favouriteQuizzes;

    public function __construct()
    {
        $this->idUser = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
        $this->userPlays = new ArrayCollection();
        $this->favouriteQuizzes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getIdUser(): Collection
    {
        return $this->idUser;
    }


    public function getRoles(): array
    {
        return ['ROLE_' . strtoupper($this->role->getRoleName())];
    }

    public function eraseCredentials(): void
    {
        
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes->add($quiz);
            $quiz->setOwner($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getOwner() === $this) {
                $quiz->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserPlay>
     */
    public function getUserPlays(): Collection
    {
        return $this->userPlays;
    }

    public function addUserPlay(UserPlay $userPlay): static
    {
        if (!$this->userPlays->contains($userPlay)) {
            $this->userPlays->add($userPlay);
            $userPlay->setPlayer($this);
        }

        return $this;
    }

    public function removeUserPlay(UserPlay $userPlay): static
    {
        if ($this->userPlays->removeElement($userPlay)) {
            // set the owning side to null (unless already changed)
            if ($userPlay->getPlayer() === $this) {
                $userPlay->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavouriteQuiz>
     */
    public function getFavouriteQuizzes(): Collection
    {
        return $this->favouriteQuizzes;
    }

    public function addFavouriteQuiz(FavouriteQuiz $favouriteQuiz): static
    {
        if (!$this->favouriteQuizzes->contains($favouriteQuiz)) {
            $this->favouriteQuizzes->add($favouriteQuiz);
            $favouriteQuiz->setPatron($this);
        }

        return $this;
    }

    public function removeFavouriteQuiz(FavouriteQuiz $favouriteQuiz): static
    {
        if ($this->favouriteQuizzes->removeElement($favouriteQuiz)) {
            // set the owning side to null (unless already changed)
            if ($favouriteQuiz->getPatron() === $this) {
                $favouriteQuiz->setPatron(null);
            }
        }

        return $this;
    }
}
