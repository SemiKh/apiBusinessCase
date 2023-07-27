<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\SousCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SousCategorieRepository::class)]
#[ApiResource(
    itemOperations:[
        'get'=> [
            'normalization_context' => [
                'groups' => 'sousCategory:item'
            ]
        ]
    ],
    collectionOperations:[
        'get'=> [
            'normalization_context' => [
                'groups' => 'sousCategory:list'
            ]
        ]
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties:[
        'nomSousCategorie' => 'partial',
        'categorie.nomCategorie'=>'partial',
    ]
)]
class SousCategorie implements SlugInterface
{ 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sousCategory:list', 'sousCategory:item', 'nft:post', 'nft:list', 'nft:item'])]
    private ?string $nomSousCategorie = null;

    #[ORM\OneToMany(mappedBy: 'SousCategories', targetEntity: Nft::class)]
    private Collection $nfts;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[Groups(['sousCategory:list', 'sousCategory:item', 'nft:post', 'nft:list', 'nft:item'])]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sousCategory:list', 'sousCategory:item', 'nft:post', 'nft:list', 'nft:item'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->nfts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSousCategorie(): ?string
    {
        return $this->nomSousCategorie;
    }

    public function setNomSousCategorie(string $nomSousCategorie): static
    {
        $this->nomSousCategorie = $nomSousCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Nft>
     */
    public function getNfts(): Collection
    {
        return $this->nfts;
    }

    public function addNft(Nft $nft): static
    {
        if (!$this->nfts->contains($nft)) {
            $this->nfts->add($nft);
            $nft->setSousCategories($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nfts->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getSousCategories() === $this) {
                $nft->setSousCategories(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

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
        return $this->nomSousCategorie;
    }
}
