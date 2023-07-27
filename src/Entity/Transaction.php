<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ApiResource(
    itemOperations:['get'],
    collectionOperations:['get','post']
)]
#[ApiFilter(
    SearchFilter::class, properties:[
        'dateAcquisition'=> 'partial',
        'nft.titre'=>'partial',
    ]
)]
#[ApiFilter(
    DateFilter::class, properties:[
        'dateAcquisition'
    ]
)]
class Transaction implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['nft:post', 'nft:list', 'nft:item','user:post', 'user:list', 'user:item'])]
    private ?\DateTimeInterface $dateAcquisition = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[Groups(['user:post', 'user:list', 'user:item'])]
    private ?Nft $nft = null;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: User::class)]
    #[Groups(['nft:post', 'nft:list', 'nft:item'])]
    private Collection $users;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:post', 'nft:list', 'nft:item','user:post', 'user:list', 'user:item'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAcquisition(): ?\DateTimeInterface
    {
        return $this->dateAcquisition;
    }

    public function setDateAcquisition(\DateTimeInterface $dateAcquisition): static
    {
        $this->dateAcquisition = $dateAcquisition;

        return $this;
    }

    public function getNft(): ?Nft
    {
        return $this->nft;
    }

    public function setNft(?Nft $nft): static
    {
        $this->nft = $nft;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setTransaction($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTransaction() === $this) {
                $user->setTransaction(null);
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

    public function getNom(): ?string{
        return $this->nft->getTitre();
    }
}
