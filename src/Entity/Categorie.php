<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'category:item'
            ]
        ]
    ],
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'category:list'
            ]
        ],
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'nomCategorie' => 'partial'
    ]
)]
class Categorie implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sousCategory:list', 'sousCategory:item', 'nft:post', 'nft:list', 'nft:item', 'category:item'])]
    private ?string $nomCategorie = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: SousCategorie::class)]
    private Collection $categories;

    #[ORM\Column(length: 255)]
    #[Groups(['sousCategory:list', 'sousCategory:item', 'nft:post', 'nft:list', 'nft:item', 'category:item'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
        return $this->categories;
    }

    public function addCategory(SousCategorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setCategorie($this);
        }

        return $this;
    }

    public function removeCategory(SousCategorie $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategorie() === $this) {
                $category->setCategorie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->getNomCategorie();
    }
}