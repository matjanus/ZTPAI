<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\Column(length: 60)]
    private ?string $password = null;

    #[ORM\Column(length: 40)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'id_user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'id_user', orphanRemoval: true)]
    private Collection $id_session;

    public function __construct()
    {
        $this->id_session = new ArrayCollection();
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

    /**
     * @return Collection<int, Session>
     */
    public function getIdSession(): Collection
    {
        return $this->id_session;
    }

    public function addIdSession(Session $idSession): static
    {
        if (!$this->id_session->contains($idSession)) {
            $this->id_session->add($idSession);
            $idSession->setIdUser($this);
        }

        return $this;
    }

    public function removeIdSession(Session $idSession): static
    {
        if ($this->id_session->removeElement($idSession)) {
            // set the owning side to null (unless already changed)
            if ($idSession->getIdUser() === $this) {
                $idSession->setIdUser(null);
            }
        }

        return $this;
    }
}
