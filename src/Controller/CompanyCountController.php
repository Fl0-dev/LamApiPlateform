<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyCountController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository)
    {

    } 

    public function __invoke(): int
    {
        return $this->companyRepository->count([]);
    }

}