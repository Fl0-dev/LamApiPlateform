<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Dependency;
use App\Repository\DependencyRepository;

class DependencyDataPersister implements ContextAwareDataPersisterInterface
{

    public function __construct(private DependencyRepository $dependencyRepository){}
          

    /**
     * Vérirfie si on a bien un objet de type Dependency
     *
     * @param [type] $data
     * @param array $context
     * @return boolean
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Dependency;
    }
    
    public function persist($data, array $context = [])
    {
        $this->dependencyRepository->persist($data); 
    }

    public function remove($data, array $context = [])
    {
        $this->dependencyRepository->remove($data);
    } 

    
    


}