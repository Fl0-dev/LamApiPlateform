<?php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CompanyContextBuilder implements SerializerContextBuilderInterface
{

    //permet de récupérer le context lors de la serialization et les authorisations de l'utilisateur
    public function __construct(private SerializerContextBuilderInterface $decorated, private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    //selon l'autorisation rajoute un group de normalization
    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if($resourceClass === Company::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_USER')) {
            $context['groups'][] = 'read:getAll:User';
        }

        return $context;
        
    }
}