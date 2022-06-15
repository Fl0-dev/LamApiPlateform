<?php

namespace App\Controller;

use App\Repository\ApplicantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller relié à l'entité Applicant
 * et appeler par l'API
 */
class ApplicantCountController extends AbstractController
{
    /**
     * constructeur pour appeler le repository
     * car il n'est pas possible de le faire dans la function __invoke
     *
     */
    public function __construct(private ApplicantRepository $applicantRepository)
    {
        
    }

    /**
     * fonction pour compter le nombre de candidats
     *
     */
    public function __invoke(Request $request): int
    {
        $activeQuery = $request->query->get('active');
        if ($activeQuery == 1) {
            return $this->applicantRepository->count(['status' => true]);
        } 
        elseif ($activeQuery == 0) {
            return $this->applicantRepository->count(['status' => false]);
        }
        elseif ($activeQuery == '') {
            return $this->applicantRepository->count([]);
        }
    }
}