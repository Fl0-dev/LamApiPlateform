<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\MeController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    security: 'is_granted("ROLE_USER")', //permet de restreindre l'accès à l'API sur la class user
    collectionOperations: [],
    itemOperations: [
        'get' => [
            'controller' => NotFoundAction::class, //cache la route
            'openapi_context' => ['summary' => 'hidden'],
            'read' => false,
            'output' => false,
        ],
        'me' => [
            'pagination_enabled' => false,
            'path' => '/me',
            'method' => 'GET',
            'controller' => MeController::class,
            'read' => false,

            //'security' => 'is_granted("ROLE_USER")',// on ne peut pas récupérer l'utilisateur si on n'est pas connecté
            'openapi_context' => [
                'security' => ['cookieAuth' => []],// on ne peut récupérer que si l'authentification est faite avec un cookie (voir OpenApiFactory)
            ]
        ],
    ],
    normalizationContext: [
        'groups' => ['user:read'], //permet de selectionner les données à renvoyer
    ],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:read'])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:read'])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
