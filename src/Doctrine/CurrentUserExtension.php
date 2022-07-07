<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Company;
use App\Entity\User;
use App\Entity\UserOwnedInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    //injecte le security service pour récupérer l'utilisateur connecté
    public function __construct( private Security $security)
    {
    }

    private function addWhere(string $resourceClass, QueryBuilder $queryBuilder): void
    {
        $reflectionClass = new \ReflectionClass($resourceClass);//ReflexionClass permet de récupérer les informations de la classe passée en paramètre
        if ($reflectionClass->implementsInterface(UserOwnedInterface::class)) {
            $rootAlias = $queryBuilder->getRootAliases()[0];//récupère la requête

            $user = $this->security->getUser();//récupère l'utilisateur connecté 

            // if ($user) {//si connexion, on ne prend que les class en lien avec l'utilisateur connecté
            //     $queryBuilder->andWhere("$rootAlias.user = :current_user")//le rajoute dans la requête
            //         ->setParameter('current_user', $this->security->getUser()->getId()); //fonctionne quand même  
            // } else {//sinon, on prend toutes les class publiques
            //     $queryBuilder->andWhere("$rootAlias.user IS NULL");
            // }
        }
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {

        if ($resourceClass !== Company::class) {
            return;
        }

        $user = $this->security->getUser();
        if (null === $user) {
            return;
        }

        $this->addWhere($resourceClass, $queryBuilder);
        
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if ($resourceClass !== Company::class) {
            return;
        }

        $user = $this->security->getUser();
        if (null === $user) {
            return;
        }

        $this->addWhere($resourceClass, $queryBuilder);
    }
    
}