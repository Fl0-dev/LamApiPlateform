<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Dependency;
use Ramsey\Uuid\Uuid;

class DependencyDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{

    public function __construct(private string $rootPath)//le chemin racine, on l'injecte dans service.yaml
    {}


    public function getDependencies() : array
    {
        $path = $this->rootPath . '/composer.json'; //va chercher le fichier composer.json
        $json = json_decode(file_get_contents($path), true); // le transforme en tableau associatif
        return $json['require'] ?? []; // on récupère le tableau require
    }
    /**
     * récupère un tableau des données de chaque dépendance 
     * contenue dans composer.json
     *
     * @param string $resourceClass : le nom de la class
     * @param string|null $operationName : le nom de la méthode (GET...)
     * @param array $context : l'ensemble des des infos
     * @return array
     */
    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {
        $items = [];
        foreach ($this->getDependencies() as $name => $version) { // pour chaque dépendance
            $items[] = new Dependency(Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString(), $name, $version); // on crée un objet Dependency (en générant un uuid)
        } 
        return $items;
    }

    //permet de restreindre le dataProvider à la class Dependency
    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Dependency::class;
    }

    /**
     * Undocumented function
     *
     * @param string $resourceClass
     * @param Uuid $id
     * @param string|null $operationName
     * @param array $context
     * @return void
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $dependencies = $this->getDependencies();
        foreach ($dependencies as $name => $version) {
            $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();
            if ($uuid === $id) {
                return new Dependency($uuid, $name, $version);
            }
        }
        return null;
    }
}