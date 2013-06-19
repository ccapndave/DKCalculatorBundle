<?php
namespace DK\CalculatorBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EntityListener {

    protected $metadataFactory;

    protected $container;

    private $entitiesToCalculate;

    public function __construct(MetadataFactoryInterface $metadataFactory, ContainerInterface $container) {
        $this->metadataFactory = $metadataFactory;
        $this->container = $container;
    }

    public function onFlush(OnFlushEventArgs $args) {
        $uow = $args->getEntityManager()->getUnitOfWork();

        // Don't worry about the observers for the moment - just set stuff when it changes
        $this->entitiesToCalculate = array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates());
    }

    public function postFlush(PostFlushEventArgs $args) {
        foreach ($this->entitiesToCalculate as $entity) {
            $this->calculate($entity);
        }
    }

    private function calculate($entity) {
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
                $em = $this->container->get("doctrine.orm.entity_manager");

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
