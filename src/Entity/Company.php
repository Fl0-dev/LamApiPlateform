<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Attribute\ApiAuthGroups;
use App\Controller\CompanyCountController;
use App\Controller\CompanyImageController;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ApiResource(
    security: 'is_granted("ROLE_USER")',
    normalizationContext: [
        'groups' => ['read:getAll', 'read:Company'] //indique l'annotation à utiliser pour récupérer certains champs lors d'un GET All
    ],
    itemOperations: [
        'get' => [ //indique les champs à récupérer lors d'un GETBy
            'normalization_context' => ['groups' => ['read:getAll', 'read:getBy', 'read:Company']],
            'openapi_context' => [
                'security' => [
                    ['bearerAuth' => []],
                ],
            ]
        ],
        'put' => [
            'denormalization_context' => ['groups' => ['write:put', 'write:Company']]
        ],
        'delete' => [
            'denormalization_context' => ['groups' => ['write:delete', 'write:Company']]
        ],
        'image' => [
            'method' => 'POST',
            'path' => '/company/{id}/image',
            'deserialize' => false, //pour éviter de deserializer le fichier
            'controller' => CompanyImageController::class,
        ]

    ],
    collectionOperations: [
        'get' => [
            'openapi_context' => [
                'security' => [
                    ['bearerAuth' => []],
                ],
            ]
        ],
        'count' => [
            'method' => 'GET',
            'path' => '/companies/count',
            'controller' => CompanyCountController::class,
            'pagination_enabled' => false, //enlève la pagination de l'API
            'filters' => [], //enlève les filtres de l'API
            'openapi_context' => [
                'summary' => 'Récupère le nombre de compagnies',
                'description' => 'Récupère le nombre de compagnies',
                'parameters' => [], //enlève les paramètres de l'API
                'security' => [
                    ['bearerAuth' => []],
                ],
            ]
        ]
    ],
),
ApiAuthGroups([
    'CAN_EDIT' => ['read:getAll:Owner'],
    'ROLE_USER' => ['read:getAll:User']
]),
]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company implements UserOwnedInterface //interface qui permet de gérer en fonction du user
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:getAll", "read:getBy"])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:getAll", "read:getBy", "write:put"])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read:getBy", "write:put", "read:getAll"])]
    private $logo;

    /**
     * @var File|null
    * @Vich\UploadableField(mapping="company_logo", fileNameProperty="logo")
    */ 
    private ?File $file = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:getBy", "write:put", "read:getAll:User"])]
    private $slug;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:getAll", "read:getBy", "write:put"])]
    private $webAddress;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:getAll", "read:getBy", "write:put"])]
    private $rhAddress;

    #[ORM\Column(type: 'text')]
    #[Groups(["read:getAll", "read:getBy", "write:put"])]
    private $description;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:getBy", "read:getAll:Owner"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:getBy", "write:put", "read:getAll:Owner"])]
    private $modifiedAt;

    #[ORM\ManyToOne(targetEntity: Workforce::class, inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:getAll:User", "read:getBy", "write:put"])]
    private $workforce;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Office::class, orphanRemoval: true)]
    private $offices;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'companies')]
    #[Groups(["read:getAll:Owner"])]
    private $user;

    public function __construct()
    {
        $this->offices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getWebAddress(): ?string
    {
        return $this->webAddress;
    }

    public function setWebAddress(string $webAddress): self
    {
        $this->webAddress = $webAddress;

        return $this;
    }

    public function getRhAddress(): ?string
    {
        return $this->rhAddress;
    }

    public function setRhAddress(string $rhAddress): self
    {
        $this->rhAddress = $rhAddress;

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

    public function getWorkforce(): ?Workforce
    {
        return $this->workforce;
    }

    public function setWorkforce(?Workforce $workforce): self
    {
        $this->workforce = $workforce;

        return $this;
    }

    /**
     * @return Collection<int, Office>
     */
    public function getOffices(): Collection
    {
        return $this->offices;
    }

    public function addOffice(Office $office): self
    {
        if (!$this->offices->contains($office)) {
            $this->offices[] = $office;
            $office->setCompany($this);
        }

        return $this;
    }

    public function removeOffice(Office $office): self
    {
        if ($this->offices->removeElement($office)) {
            // set the owning side to null (unless already changed)
            if ($office->getCompany() === $this) {
                $office->setCompany(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
