<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_emprunt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_fin_prevue = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_retour = null;

    #[ORM\ManyToMany(targetEntity: Livre::class, mappedBy: 'Emprunt', orphanRemoval: false)]
    private Collection $livres;

    #[ORM\ManyToOne(inversedBy: 'Emprunt')]
    private ?Adherent $adherent = null;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeImmutable
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTimeImmutable $date_emprunt): self
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getDateFinPrevue(): ?\DateTimeImmutable
    {
        return $this->date_fin_prevue;
    }

    public function setDateFinPrevue(\DateTimeImmutable $date_fin_prevue): self
    {
        $this->date_fin_prevue = $date_fin_prevue;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeImmutable
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeImmutable $date_retour): self
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->addEmprunt($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            $livre->removeEmprunt($this);
        }

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}
