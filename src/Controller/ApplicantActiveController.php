<?php

namespace App\Controller;

use App\Entity\Applicant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller relié à l'entité Applicant
 * et appeler par l'API
 */
class ApplicantActiveController extends AbstractController
{  
    /**
     * fonction pour activer un candidat
     * le param doit être nommer $data obligatoirement
     *
     * @param Applicant $data
     * @return Applicant
     */
    public function __invoke(Applicant $data): Applicant
    {
        $data->setStatus(true);

        return $data;
    }
    
}