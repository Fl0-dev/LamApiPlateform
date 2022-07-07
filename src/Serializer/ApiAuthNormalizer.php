<?php

namespace App\Serializer;

use App\Attribute\ApiAuthGroups;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class ApiAuthNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED_NORMALIZER = 'ApiAuthNormalizerAlreadyCalled'; //clef pour éviter l'appel infini de la méthode normalize

    public function __construct(private AuthorizationCheckerInterface $authorizationChecker)
    {
    }
    
    //permet de mettre en place la normalization selon l'instance de l'entité
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (!is_object($data)) {
            return false;
        }

        $class = new \ReflectionClass(get_class($data));//récupération de la class de l'entité normalisée
        $classAttributes = $class->getAttributes(ApiAuthGroups::class);//récupération des attributs qui appartiennent à la class ApiAuthGroups
        $alreadyCalled = $context[self::ALREADY_CALLED_NORMALIZER] ?? false;
        return !$alreadyCalled && !empty($classAttributes);//si l'attribut ApiAuthGroups est présent dans la class, on normalise l'entité
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $class = new \ReflectionClass(get_class($object));//récupération de la class de l'entité normalisée
        $apiAuthGroups = $class->getAttributes(ApiAuthGroups::class)[0]->newInstance();//récupération des attributs qui appartiennent à la class ApiAuthGroups

        foreach ($apiAuthGroups->groups as $role => $groups) {
            if ($this->authorizationChecker->isGranted($role, $object)) {
                $context['groups'] = array_merge($context['groups'] ?? [], $groups);//ajout des groupes à la normalisation
            }
        }
        $context[self::ALREADY_CALLED_NORMALIZER] = true;
        return $this->normalizer->normalize($object, $format, $context);

        // $context[self::ALREADY_CALLED_NORMALIZER] = true;
        // // On check avec le voter si l'utilisateur a le droit d'éditer la compagnie
        // if($this->authorizationChecker->isGranted(UserOwnedVoter::CAN_EDIT, $object) && isset($context['groups'])) {
        //     $context['groups'][] = 'read:getAll:User';
        // }
        // return $this->normalizer->normalize($object, $format, $context);
    }
}