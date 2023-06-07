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
    private ?\DateTimeImmutable $date_de_parution = null;

    #[ORM\Column]
    private ?int $nombre_de_pages = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    private ?Auteur $Auteur = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    private Collection $Catégorie;

    #[ORM\ManyToMany(targetEntity: Emprunt::class, inversedBy: 'livres')]
    private Collection $Emprunt;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    public function __construct()
    {
        $this->Catégorie = new ArrayCollection();
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

    public function getDateDeParution(): ?\DateTimeImmutable
    {
        return $this->date_de_parution;
    }

    public function setDateDeParution(\DateTimeImmutable $date_de_parution): self
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
        return $this->Auteur;
    }

    public function setAuteur(?Auteur $Auteur): self
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCatégorie(): Collection
    {
        return $this->Catégorie;
    }

    public function addCatGorie(Categorie $catGorie): self
    {
        if (!$this->Catégorie->contains($catGorie)) {
            $this->Catégorie->add($catGorie);
        }

        return $this;
    }

    public function removeCatGorie(Categorie $catGorie): self
    {
        $this->Catégorie->removeElement($catGorie);

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
