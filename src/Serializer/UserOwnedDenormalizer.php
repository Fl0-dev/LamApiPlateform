<?php

namespace App\Serializer;

use App\Entity\UserOwnedInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class UserOwnedDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;//permet de récupérer le denormalizer

    private const ALREADY_CALLED_DENORMALIZER = 'UserOwnedDenormalizerCAlled';//permet de savoir si le denormalizer a déjà été appelé

    public function __construct(private Security $security){}

    //permet de récupérer le user en appelant l'interface UserOwnedInterface à chaque Denormalization
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        $reflectionClass = new \ReflectionClass($type);//ReflexionClass permet de récupérer les informations de la classe passée en paramètre
        $alreadyCalledd = $context[self::ALREADY_CALLED_DENORMALIZER] ?? false;//permet de savoir si le denormalizer a déjà été appelé

        return $reflectionClass->implementsInterface(UserOwnedInterface::class) && !$alreadyCalledd;//permet de savoir si la classe passée en paramètre implémente l'interface UserOwnedInterface et si le denormalizer n'a pas déjà été appelé
    }
    
    public function denormalize($data, string $type, $format = null, array $context = [])
    {

        $context[self::ALREADY_CALLED_DENORMALIZER] = true;//permet d'indiquer que le denormalizer est appelé'
        
        /** @var UserOwnedInterface $obj */   //type de l'obj
        $obj = $this->denormalizer->denormalize($data, $type, $format, $context);//récupère l'objet qui se denormalize (en attente de sauvegarde)
        $obj->setUser($this->security->getUser());//définit l'utilisateur connecté dans l'objet
        return $obj;
    }

    
}