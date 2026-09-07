<?php

namespace App\Entity;

use App\Repository\SessionsJeuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionsJeuRepository::class)]
class SessionsJeu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, )]
    private ?\DateTime $debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE,)]
    private ?\DateTime $fin = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'sessionsJeus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Journee $Journee = null;

    #[ORM\ManyToOne(inversedBy: 'sessionsJeus')]
    private ?Console $Console = null;

    // #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    // private ?\DateTime $arra = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDebut(): ?\DateTime
    {
        return $this->debut;
    }

    public function setDebut(\DateTime $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTime
    {
        return $this->fin;
    }

    public function setFin(\DateTime $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getJournee(): ?Journee
    {
        return $this->Journee;
    }

    public function setJournee(?Journee $Journee): static
    {
        $this->Journee = $Journee;

        return $this;
    }

    public function getConsole(): ?Console
    {
        return $this->Console;
    }

    public function setConsole(?Console $Console): static
    {
        $this->Console = $Console;

        return $this;
    }


}
