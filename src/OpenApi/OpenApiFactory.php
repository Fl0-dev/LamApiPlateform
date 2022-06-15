<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);//permet de récupérer l'OpenApi décoré

        //permet de supprimer un summary donné dans l'OpenApi
        foreach ($openApi->getPaths()->getPaths() as $path => $pathItem) {//on récupère les paths
            if ($pathItem->getGet() && $pathItem->getGet()->getSummary() === 'hidden') {//on récupère les paths dont le summary est 'hidden'
                $openApi->getPaths()->addPath($path, $pathItem->withGet(null));//on supprime le get
            }
        }

        //permet de construire une opération dans l'OpenApi
        //$openApi->getPaths()->addPath('/repository/workforces', new PathItem(null,'Workforce', null, new Operation('AllWorkforces',[],[], 'Récupère la liste des workforces')));//on ajoute un path pour la réponse de la requête /repository/workforces
        return $openApi;
    }
}
