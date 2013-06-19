<?php
namespace DK\CalculatorBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

class EntityListener {

    private $calculator;

    private $entitiesToCalculate;

    public function __construct($calculator) {
        $this->calculator = $calculator;
    }

    public function onFlush(OnFlushEventArgs $args) {
        $uow = $args->getEntityManager()->getUnitOfWork();

        // Don't worry about the observers for the moment - just set stuff when it changes
        $this->entitiesToCalculate = array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates());
    }

    public function postFlush(PostFlushEventArgs $args) {
        foreach ($this->entitiesToCalculate as $entity) {
            $this->calculator->calculate($entity);
        }
    }

}
