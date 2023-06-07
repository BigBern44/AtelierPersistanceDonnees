<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?\DateTime $date_de_parution = null;

    #[ORM\Column]
    private ?int $nombre_de_pages = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    private ?Auteur $auteur = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    private Collection $Categorie;

    #[ORM\ManyToMany(targetEntity: Emprunt::class, inversedBy: 'livres')]
    private Collection $Emprunt;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    public function __construct()
    {
        $this->Categorie = new ArrayCollection();
        $this->Emprunt = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDeParution(): ?\DateTime
    {
        return $this->date_de_parution;
    }

    public function setDateDeParution(\DateTime $date_de_parution): self
    {
        $this->date_de_parution = $date_de_parution;

        return $this;
    }

    public function getNombreDePages(): ?int
    {
        return $this->nombre_de_pages;
    }

    public function setNombreDePages(int $nombre_de_pages): self
    {
        $this->nombre_de_pages = $nombre_de_pages;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorie(): Collection
    {
        return $this->Categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->Categorie->contains($categorie)) {
            $this->Categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->Categorie->removeElement($categorie);

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunt(): Collection
    {
        return $this->Emprunt;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->Emprunt->contains($emprunt)) {
            $this->Emprunt->add($emprunt);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        $this->Emprunt->removeElement($emprunt);

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
