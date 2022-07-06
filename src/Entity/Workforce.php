<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\WorkforceGetAllController;
use Doctrine\ORM\Mapping as ORM;

enum EnumWorkforce: string
{
    case LEVEL_1 = '1-a-9-salaries';
    case LEVEL_2 = '10-a-19-salaries';
    case LEVEL_3 = '20-a-49-salaries';
    case LEVEL_4 = '50-a-99-salaries';
    case LEVEL_5 = '100-a-199-salaries';
    case LEVEL_6 = '200-a-499-salaries';
    case LEVEL_7 = '500-a-999-salaries';
    case LEVEL_8 = '1000-a-1999-salaries';
    case LEVEL_9 = '2000-a-4999-salaries';
    case LEVEL_10 = '5000-a-9999-salaries';
    case LEVEL_11 = '+-de-10000-salaries';
}

#[ApiResource(
    collectionOperations: [
        'getAllLabel' => [
            'method' => 'GET',
            'path' => '/workforces/labels',
            'controller' => WorkforceGetAllController::class,
            'pagination_enabled' => false,
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Récupère toutes les valeurs des labels',
            ]
            ],
            'get'
    ]
)]
#[ORM\Entity]
class Workforce
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $label;

    #[ORM\OneToMany(mappedBy: 'workforce', targetEntity: Company::class)]
    private $companies;

    /**
     * Get the value of label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @return  self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of companies
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set the value of companies
     *
     * @return  self
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
