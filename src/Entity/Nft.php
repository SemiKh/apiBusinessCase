<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftRepository::class)]
#[ApiResource]
class Nft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $jeton = null;

    #[ORM\Column]
    private ?float $valeur = null;

    #[ORM\Column]
    private ?int $nombreDisponible = null;

    #[ORM\Column(length: 255)]
    private ?string $cheminStockage = null;

    #[ORM\Column(length: 255)]
    private ?string $createur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDrop = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $types = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?CoursNft $cours = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?SousCategorie $SousCategories = null;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?User $user = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getJeton(): ?string
    {
        return $this->jeton;
    }

    public function setJeton(string $jeton): static
    {
        $this->jeton = $jeton;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getNombreDisponible(): ?int
    {
        return $this->nombreDisponible;
    }

    public function setNombreDisponible(int $nombreDisponible): static
    {
        $this->nombreDisponible = $nombreDisponible;

        return $this;
    }

    public function getCheminStockage(): ?string
    {
        return $this->cheminStockage;
    }

    public function setCheminStockage(string $cheminStockage): static
    {
        $this->cheminStockage = $cheminStockage;

        return $this;
    }

    public function getCreateur(): ?string
    {
        return $this->createur;
    }

    public function setCreateur(string $createur): static
    {
        $this->createur = $createur;

        return $this;
    }

    public function getDateDrop(): ?\DateTimeInterface
    {
        return $this->dateDrop;
    }

    public function setDateDrop(\DateTimeInterface $dateDrop): static
    {
        $this->dateDrop = $dateDrop;

        return $this;
    }

    public function getTypes(): ?Type
    {
        return $this->types;
    }

    public function setTypes(?Type $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getCours(): ?CoursNft
    {
        return $this->cours;
    }

    public function setCours(?CoursNft $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    public function getSousCategories(): ?SousCategorie
    {
        return $this->SousCategories;
    }

    public function setSousCategories(?SousCategorie $SousCategories): static
    {
        $this->SousCategories = $SousCategories;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setNft($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getNft() === $this) {
                $transaction->setNft(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
