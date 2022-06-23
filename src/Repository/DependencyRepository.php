<?php
namespace App\Repository;

use App\Entity\Dependency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DependencyRepository extends ServiceEntityRepository
{
    public function __construct(private string $rootPath)//le chemin racine, on l'injecte dans service.yaml
    {}


    
    private function getDependencies(): array
    {
        $path = $this->rootPath . '/composer.json'; //va chercher le fichier composer.json
        $json = json_decode(file_get_contents($path), true); // le transforme en tableau associatif
        return $json['require'] ?? []; // on récupère le tableau require
    }

    /**
     * @return Dependency[]
     */
    public function findAll(): array
    {
        $dependencies = $this->getDependencies();
        $items = [];
        foreach ($dependencies as $name => $version) {
            $items[] = new Dependency($name, $version);
        }
        return $items;
    }

    public function findByUuid(string $uuid): ?Dependency
    {
        $dependencies = $this->findAll();
        foreach ($dependencies as $dependency) {
            if ($dependency->getUuid() === $uuid) {
                return $dependency;
            }
        }
        return null;
    }

    public function persist(Dependency $dependency)
    {
        $path = $this->rootPath . '/composer.json';//on récupère le chemin
        $json = json_decode(file_get_contents($path), true);//on transforme en tableau associatif
        $json['require'][$dependency->getName()];//on ajoute la dépendance dans le tableau
        file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));//on écrit le tableau associatif dans le fichier composer.json
    }

    public function remove(Dependency $dependency)
    {
        $path = $this->rootPath . '/composer.json';
        $json = json_decode(file_get_contents($path), true);
        unset($json['require'][$dependency->getName()]);
        file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}