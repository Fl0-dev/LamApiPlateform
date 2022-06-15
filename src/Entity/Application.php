<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ApiResource(
    normalizationContext: [
        'groups' => ['read:getAll', 'read:Application'] //indique l'annotation à utiliser pour récupérer certains champs lors d'un GET All
    ],
    itemOperations: [
        'get' => [ //indique les champs à récupérer lors d'un GETBy
            'normalization_context' => ['groups' => ['read:getAll', 'read:getBy', 'read:Application']]
        ]
        ],
    collectionOperations: [
        'post' => [
            'validation_groups' => [Application::class, 'getValidationGroups']//indique les groupes de validation à utiliser pour un POST
        ]   
    ]
)]
#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:getAll", "read:Applicant"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:getAll"])]
    private $cv;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(["read:getAll", "read:Applicant"]),
      Length(min: 5, groups: ["write:Application"])
    ]
    private $message;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:getBy", "read:Applicant"])]
    private $submitDate;

    #[ORM\ManyToOne(targetEntity: Applicant::class, inversedBy: 'applications')]
    #[Groups(["read:getBy"])]
    private $applicant;

    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'applications')]
    #[Groups(["read:getBy", "read:Applicant"])]
    private $offer;

    /**
     * renvoie les groupes de validation à utiliser pour la validation de l'entité
     *
     * @param self $application
     * @return array
     */
    public static function getValidationGroups(self $application): array
    {
        return ["write:Application"];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSubmitDate(): ?\DateTimeInterface
    {
        return $this->submitDate;
    }

    public function setSubmitDate(\DateTimeInterface $submitDate): self
    {
        $this->submitDate = $submitDate;

        return $this;
    }

    public function getApplicant(): ?Applicant
    {
        return $this->applicant;
    }

    public function setApplicant(?Applicant $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }
}
