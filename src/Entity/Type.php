<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource(
    itemOperations:['get'],
    collectionOperations:['get']
)]
#[ApiFilter(
    SearchFilter::class, properties:[
        'theme' => 'partial',
        'extension' => 'partial',
        'bibliothequeNFT' => 'partial'

    ]
)]
class Type implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:post', 'nft:list', 'nft:item', 'user:post', 'user:list', 'user:item'])]
    private ?string $theme = null;

    #[ORM\Column(length: 5)]
    #[Groups(['nft:post', 'nft:list', 'nft:item', 'user:post', 'user:list', 'user:item'])]
    private ?string $extension = null;

    #[ORM\Column(length: 255)]
    private ?string $bibliothequeNFT = null;

    #[ORM\OneToMany(mappedBy: 'types', targetEntity: Nft::class)]
    private Collection $nfts;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:post', 'nft:list', 'nft:item', 'user:post', 'user:list', 'user:item'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->nfts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

    public function getBibliothequeNFT(): ?string
    {
        return $this->bibliothequeNFT;
    }

    public function setBibliothequeNFT(string $bibliothequeNFT): static
    {
        $this->bibliothequeNFT = $bibliothequeNFT;

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
            $nft->setTypes($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nfts->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getTypes() === $this) {
                $nft->setTypes(null);
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

    public function getNom(): ?string {
        return $this->theme;
    }
}
