<?php
namespace DK\CalculatorBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EntityListener {

    protected $metadataFactory;

    public function __construct(MetadataFactoryInterface $metadataFactory) {
        $this->metadataFactory = $metadataFactory;
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($entity));
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->calculator)) {
                $calculatorClassName = $propertyMetadata->calculator->class;
                $calculatorClass = new $calculatorClassName();
                $calculatorMethod = "get".ucfirst($propertyMetadata->name);
                $result = $calculatorClass->$calculatorMethod($entity, $args->getEntityManager());
                $propertyMetadata->setValue($entity, $result);
            }
        }
    }

}