<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CoursNftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursNftRepository::class)]
#[ApiResource(
    collectionOperations:[
        'get', 
        'post'
    ],
    itemOperations:[
        'get',
    ]
)]
class CoursNft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $coursNft24h = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateNft = null;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Nft::class)]
    private Collection $nfts;

    public function __construct()
    {
        $this->nfts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoursNft24h(): ?float
    {
        return $this->coursNft24h;
    }

    public function setCoursNft24h(float $coursNft24h): static
    {
        $this->coursNft24h = $coursNft24h;

        return $this;
    }

    public function getDateNft(): ?\DateTimeInterface
    {
        return $this->dateNft;
    }

    public function setDateNft(\DateTimeInterface $dateNft): static
    {
        $this->dateNft = $dateNft;

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
            $nft->setCours($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nfts->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getCours() === $this) {
                $nft->setCours(null);
            }
        }

        return $this;
    }

    
}
