<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    itemOperations:['get'],
    collectionOperations:['get']
)]
#[ApiFilter(
    SearchFilter::class, properties:[
        'nomCategorie'=>'partial'
    ]
)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCategorie = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: SousCategorie::class)]
    private Collection $Categories;

    public function __construct()
    {
        $this->Categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): static
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * @return Collection<int, SousCategorie>
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addCategory(SousCategorie $category): static
    {
        if (!$this->Categories->contains($category)) {
            $this->Categories->add($category);
            $category->setCategorie($this);
        }

        return $this;
    }

    public function removeCategory(SousCategorie $category): static
    {
        if ($this->Categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategorie() === $this) {
                $category->setCategorie(null);
            }
        }

        return $this;
    }
}
