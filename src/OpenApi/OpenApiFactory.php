<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
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

        //mettre paramètre à vide pour l'endpoint me
        $meOperation = $openApi->getPaths()->getPath('/api/me')->getGet()->withParameters([]);
        $mePathItem = $openApi->getPaths()->getPath('/api/me')->withGet($meOperation);
        $openApi->getPaths()->addPath('/api/me', $mePathItem);//on ajoute le path /api/me

        $schemas = $openApi->getComponents()->getSecuritySchemes();//récupération des schémas de sécurité
        $schemas['coockieAuth'] = new \ArrayObject([ //création d'un schéma de sécurité
            'type' => 'apiKey',
            'in' => 'cookie',
            'name' => 'PHPSESSID',
        ]);
        //$openApi = $openApi->withSecurity([['coockieAuth' => []]]);//ajout du schéma de sécurité sur toutes les routes

        //création d'un schéma pour autentification
        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'JeanEudes@gmail.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'password',
                ],
            ],
        ]);

        //nouveau endpoint pour pouvoir s'autentifier
        $pathItem= new PathItem(
            post: new Operation(
                operationId: 'postApiLogin',//nom de l'opération qui doit être unique (ID)
                tags: ['Auth'],//nom de la category où doit apparaitre l'opération
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',//utilisation du schéma d'autentification
                            ],
                        ]
                    ])
                ),
                responses: [
                    '200' => [
                        'description' => 'User connected',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-user.read',// le chemein qui demande une autentitfication
                                ],
                            ],
                        ],
                    ],
                ]
            )
        );

        $openApi->getPaths()->addPath('/api/login', $pathItem);//ajout du path au OpenApi

        $pathItem= new PathItem(
            post: new Operation(
                operationId: 'postApiLogout',//nom de l'opération qui doit être unique (ID)
                tags: ['Auth'],//nom de la category où doit apparaitre l'opération
                responses: [
                    '204' => [],
                ]
            )
        );

        $openApi->getPaths()->addPath('/api/logout', $pathItem);

        //permet de construire une opération dans l'OpenApi
        //$openApi->getPaths()->addPath('/repository/workforces', new PathItem(null,'Workforce', null, new Operation('AllWorkforces',[],[], 'Récupère la liste des workforces')));//on ajoute un path pour la réponse de la requête /repository/workforces
        return $openApi;
    }
}
