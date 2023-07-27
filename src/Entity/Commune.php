<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
#[ApiResource(
    collectionOperations:[
        'post'
    ],
    itemOperations:['put', 'delete', 'get'
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties:[
        'ville'=>'partial',
        'codePostal'=>'exact',
        'departement'=>'partial',
    ]
)]
class Commune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:post', 'user:list', 'user:item'])]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:list', 'user:item'])]
    private ?string $departement = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:post', 'user:list', 'user:item'])]
    private ?string $ville = null;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: Adresse::class)]
    private Collection $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setCommune($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCommune() === $this) {
                $adress->setCommune(null);
            }
        }

        return $this;
    }
}
