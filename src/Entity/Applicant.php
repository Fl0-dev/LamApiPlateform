<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ApplicantActiveController;
use App\Controller\ApplicantCountController;
use App\Repository\ApplicantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: [
        'groups' => ['read:getAll', 'read:Applicant'], //indique l'annotation à utiliser pour récupérer certains champs lors d'un GET All
        'openapi_definition_name' => 'Collection'//pour renommer le schéma dans la documentation
    ],
    itemOperations: [
        'get' => [ //indique les champs à récupérer lors d'un GETBy
            'normalization_context' => [
                'groups' => ['read:getAll', 'read:getBy', 'read:Applicant'],
                'openapi_definition_name' => 'Détail'//pour renommer le schéma dans la documentation
            ]
        ],
        'active' => [//route personalisée
            'method' => 'POST',//indique le type de requête
            'path' => '/applicants/{id}/active',//indique le chemin de la route
            'controller' => ApplicantActiveController::class,//indique le controlleur à utiliser
            'openapi_context' => [//indique les paramètres à utiliser pour l'API et qui s'afficheront dans la doc
                'summary' => 'Active un candidat',
                'description' => 'Active un candidat',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => []
                        ]
                    ]
                ]
            ]   
        ]
    ],
    collectionOperations: [
        'get',
        'post',
        'count' => [//Il est possible de générer le code avec des fonctions
            'method' => 'GET',
            'path' => '/applicants/count',
            'controller' => ApplicantCountController::class,
            'pagination_enabled' => false, //enlève la pagination de l'API
            'filters' => [], //enlève les filtres de l'API
            'openapi_context' => [
                'summary' => 'Compte le nombre de candidats',
                'parameters' => [
                    [
                    'in' => 'query',
                    'name' => 'active',
                    'schema' => [
                        'type' => 'integer',
                        'maximum' => 1,
                        'minimum' => 0
                    ],
                    'description' => '1 pour actif, 0 pour inactif'
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Nombre de candidats',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'integer',
                                    'example' => 3
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
)]
#[ORM\Entity(repositoryClass: ApplicantRepository::class)]
class Applicant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:getAll", "read:Applicant"])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:getAll", "read:Applicant"])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:getAll", "read:Applicant"])]
    private $lastname;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:getAll", "read:Applicant"])]
    private $email;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:getBy"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:getBy"])]
    private $modifiedAt;

    #[ORM\OneToMany(mappedBy: 'applicant', targetEntity: Application::class)]
    //#[Groups(["read:getBy","read:getAll", "read:Application"])]
    private $applications;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    #[Groups(["read:getAll", "read:getBy"]),
      ApiProperty(openapiContext : [
                'description' => 'Indique si le candidat est actif ou non',
                'type' => 'boolean',
                'example' => false
            ]
    )]
    private $status = false;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

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
            $application->setApplicant($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getApplicant() === $this) {
                $application->setApplicant(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
