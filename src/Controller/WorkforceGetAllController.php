<?php

namespace App\Controller;

use App\Entity\EnumWorkforce;
use App\Entity\Workforce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkforceGetAllController extends AbstractController
{
    public function __invoke(): array
    {
        $data = EnumWorkforce::cases();
        return $data;
    }
}
