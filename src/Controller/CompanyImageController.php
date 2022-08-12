<?php
namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CompanyImageController  extends AbstractController
{
    public function __invoke(Request $request)
    {
        $company = $request->attributes->get('data');//récupération de l'entité Company via la requête
        if (!($company instanceof Company)) {
            throw new \RuntimeException('The attribute "data" must be an instance of Company');
        }
        dd($request);
        $company->setFile($request->files->get('file'));//on définit l'image de l'entité Company
        $company->setModifiedAt(new \DateTime());//on définit la date de modification de l'entité Company et on déclenche la persistence de Doctrine avec le VichUploaderBundle
        
        return $company;
    }
}