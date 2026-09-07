<?php

namespace App\Entity;

use App\Repository\JourneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JourneeRepository::class)]
class Journee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

 
    #[ORM\Column]
    private ?int $caisseOuverture = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, SessionsJeu>
     */
    #[ORM\OneToMany(targetEntity: SessionsJeu::class, mappedBy: 'Journee', orphanRemoval: true)]
    private Collection $sessionsJeus;

    #[ORM\Column(nullable: true)]
    private ?int $caisseFermeture = null;

    public function __construct()
    {
        $this->sessionsJeus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }




    public function getCaisseOuverture(): ?int
    {
        return $this->caisseOuverture;
    }

    public function setCaisseOuverture(int $caisseOuverture): static
    {
        $this->caisseOuverture = $caisseOuverture;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

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

    /**
     * @return Collection<int, SessionsJeu>
     */
    public function getSessionsJeus(): Collection
    {
        return $this->sessionsJeus;
    }

    public function addSessionsJeu(SessionsJeu $sessionsJeu): static
    {
        if (!$this->sessionsJeus->contains($sessionsJeu)) {
            $this->sessionsJeus->add($sessionsJeu);
            $sessionsJeu->setJournee($this);
        }

        return $this;
    }

    public function removeSessionsJeu(SessionsJeu $sessionsJeu): static
    {
        if ($this->sessionsJeus->removeElement($sessionsJeu)) {
            // set the owning side to null (unless already changed)
            if ($sessionsJeu->getJournee() === $this) {
                $sessionsJeu->setJournee(null);
            }
        }

        return $this;
    }

    public function getCaisseFermeture(): ?int
    {
        return $this->caisseFermeture;
    }

    public function setCaisseFermeture(?int $caisseFermeture): static
    {
        $this->caisseFermeture = $caisseFermeture;

        return $this;
    }
}
