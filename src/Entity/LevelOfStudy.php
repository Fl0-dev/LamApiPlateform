<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LevelOfStudyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ApiResource(
    normalizationContext: [
        "groups" => ["read:getAll"]
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => ["groups" => ["read:getAll"]]
        ],
        "put" => [
            "denormalization_context" => ["groups" => ["write:put"]]
        ],
        "delete" => [
            "denormalization_context" => ["groups" => ["write:delete"]]
        ]
    ]
)]
#[ORM\Entity(repositoryClass: LevelOfStudyRepository::class)]
class LevelOfStudy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:getAll"])]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(["read:getAll", "write:put", "write:delete"]),
      Length(min: 2, max: 50, groups: ["write:LevelOfStudy"])
    ]
    private $label;

    #[ORM\OneToMany(mappedBy: 'levelOfStudy', targetEntity: Offer::class)]
    private $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setLevelOfStudy($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getLevelOfStudy() === $this) {
                $offer->setLevelOfStudy(null);
            }
        }

        return $this;
    }
}
