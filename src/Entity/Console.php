<?php

namespace App\Entity;

use App\Repository\ConsoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsoleRepository::class)]
class Console
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $tarifHeure = null;

    #[ORM\Column(nullable: true)]
    private ?int $tarif15min = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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



    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTarifHeure(): ?int
    {
        return $this->tarifHeure;
    }

    public function setTarifHeure(int $tarifHeure): static
    {
        $this->tarifHeure = $tarifHeure;

        return $this;
    }

    public function getTarif15min(): ?int
    {
        return $this->tarif15min;
    }

    public function setTarif15min(?int $tarif15min): static
    {
        $this->tarif15min = $tarif15min;

        return $this;
    }


}
