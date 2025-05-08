<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $roleName = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'role')]
    private Collection $idRole;

    public function __construct()
    {
        $this->idRole = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): static
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdRole(): Collection
    {
        return $this->idRole;
    }

    public function addIdRole(User $idRole): static
    {
        if (!$this->idRole->contains($idRole)) {
            $this->idRole->add($idRole);
            $idRole->setRole($this);
        }

        return $this;
    }

    public function removeIdRole(User $idRole): static
    {
        if ($this->idRole->removeElement($idRole)) {
            // set the owning side to null (unless already changed)
            if ($idRole->getRole() === $this) {
                $idRole->setRole(null);
            }
        }

        return $this;
    }
}
