<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MeController extends AbstractController
{

    //injection de dépendance
    public function __construct(private Security $security){}

    public function __invoke()//récupère l'utilisateur connecté
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return $this->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $user;
        
    }
}