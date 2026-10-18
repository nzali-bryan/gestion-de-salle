<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creatadAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?SessionsJeu $Sessions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getmontant(): ?int
    {
        return $this->montant;
    }

    public function setmontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatadAt(): ?\DateTimeImmutable
    {
        return $this->creatadAt;
    }

    public function setCreatadAt(\DateTimeImmutable $creatadAt): static
    {
        $this->creatadAt = $creatadAt;

        return $this;
    }

    public function getSessions(): ?SessionsJeu
    {
        return $this->Sessions;
    }

    public function setSessions(?SessionsJeu $Sessions): static
    {
        $this->Sessions = $Sessions;

        return $this;
    }
}
