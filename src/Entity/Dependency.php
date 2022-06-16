<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    paginationEnabled: false,
)]
class Dependency
{
    #[ApiProperty(identifier: true)]
    private $uuid;

    #[ApiProperty(description: 'Nom de la dépendance')]
    private $name;

    #[ApiProperty(
        description: 'Version de la dépendance',
        openapiContext: [
            'example' => '5.2.*'
        ]    
    )]
    private $version;

    public function __construct(string $uuid, string $name, string $version)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->version = $version;
    }


    /**
     * Get the value of version
     */ 
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of uuid
     */ 
    public function getUuid()
    {
        return $this->uuid;
    }
}