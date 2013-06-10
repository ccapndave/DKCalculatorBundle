<?php
namespace DK\CalculatorBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EntityListener {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /*public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

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

                // Get the result and set it on the entity
                $result = $calculator->$calculatorMethod($entity, $args->getEntityManager());
                $propertyMetadata->setValue($entity, $result);
            }
        }
    }*/

    public function postFlush(PostFlushEventArgs $args) {
        echo "post flush";
    }

}

/*class EntityListener {

    protected $metadataFactory;

    protected $container;

    public function __construct(MetadataFactoryInterface $metadataFactory, ContainerInterface $container) {
        $this->metadataFactory = $metadataFactory;
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

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

                // Get the result and set it on the entity
                $result = $calculator->$calculatorMethod($entity, $args->getEntityManager());
                $propertyMetadata->setValue($entity, $result);
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args) {

    }

}*/