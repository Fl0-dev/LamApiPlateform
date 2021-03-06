<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: [
        'groups' => ['read:getAll'] //indique l'annotation à utiliser pour récupérer certains champs lors d'un GET All
    ],
    itemOperations: [
        'get' => [ //indique les champs à récupérer lors d'un GETBy
            'normalization_context' => ['groups' => ['read:getAll', 'read:getBy']]
        ]
    ]
)]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:getAll"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:getAll"])]
    private $title;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["read:getBy"])]
    private $salaryMin;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["read:getBy"])]
    private $salaryMax;

    #[ORM\Column(type: 'text')]
    #[Groups(["read:getAll"])]
    private $description;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:getAll"])]
    private $publicationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(["read:getBy"])]
    private $providedDate;

    #[ORM\ManyToOne(targetEntity: Office::class, inversedBy: 'offers')]
    #[Groups(["read:getAll"])]
    private $office;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:getBy"])]
    private $status;

    #[ORM\ManyToOne(targetEntity: LevelOfStudy::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:getAll"])]
    private $levelOfStudy;

    #[ORM\ManyToOne(targetEntity: Experience::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:getAll"])]
    private $experience;

    #[ORM\ManyToOne(targetEntity: JobTitle::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:getAll"])]
    private $jobTitle;

    #[ORM\ManyToOne(targetEntity: ContractType::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:getAll"])]
    private $contractType;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Application::class)]
    #[Groups(["read:getBy"])]
    private $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSalaryMin(): ?int
    {
        return $this->salaryMin;
    }

    public function setSalaryMin(?int $salaryMin): self
    {
        $this->salaryMin = $salaryMin;

        return $this;
    }

    public function getSalaryMax(): ?int
    {
        return $this->salaryMax;
    }

    public function setSalaryMax(?int $salaryMax): self
    {
        $this->salaryMax = $salaryMax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getProvidedDate(): ?\DateTimeInterface
    {
        return $this->providedDate;
    }

    public function setProvidedDate(?\DateTimeInterface $providedDate): self
    {
        $this->providedDate = $providedDate;

        return $this;
    }

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    public function setOffice(?Office $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLevelOfStudy(): ?LevelOfStudy
    {
        return $this->levelOfStudy;
    }

    public function setLevelOfStudy(?LevelOfStudy $levelOfStudy): self
    {
        $this->levelOfStudy = $levelOfStudy;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getJobTitle(): ?JobTitle
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?JobTitle $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getContractType(): ?ContractType
    {
        return $this->contractType;
    }

    public function setContractType(?ContractType $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }
}
