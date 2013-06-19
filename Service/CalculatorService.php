<?php
namespace DK\CalculatorBundle\Service;

use Doctrine\ORM\EntityManager;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CalculatorService {

    private $metadataFactory;

    private $container;

    public function __construct(MetadataFactoryInterface $metadataFactory, ContainerInterface $container) {
        $this->metadataFactory = $metadataFactory;
        $this->container = $container;
    }

    public function calculate($entity) {
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($entity));
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->calculator)) {
                if (isset($propertyMetadata->calculator->class)) {
                    $calculator = new $propertyMetadata->calculator->class();
                } else if (isset($propertyMetadata->calculator->service)) {
                    $calculator = $this->container->get($propertyMetadata->calculator->service);
                }

                // If the property name starts with "is" or "has" then look for a similarly named method in the calculator,
                // otherwise prefix it with "get"
                if (preg_match('/^(has|is)(.+)$/i', $propertyMetadata->name)) {
                    $calculatorMethod = $propertyMetadata->name;
                } else {
                    $calculatorMethod = "get".ucfirst($propertyMetadata->name);
                }

                // Get the result, set it on the entity then update it in the database
                $em = $this->container->get("doctrine.orm.entity_manager"); // for some reason we get a circular reference error if I inject this
                $result = $calculator->$calculatorMethod($entity, $em);
                $propertyMetadata->setValue($entity, $result);

                // Update the field in the database with some update DQL
                $qb = $em->createQueryBuilder()
                    ->update(get_class($entity), 'e')
                    ->set("e.".$propertyMetadata->name, $result)
                    ->where('e = :entity')->setParameter('entity', $entity->getId());

                $qb->getQuery()->getResult();
            }
        }
    }

}