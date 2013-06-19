<?php
namespace DK\CalculatorBundle\Tests\Service;

use Doctrine\ORM\EntityManager;

class PersonCalculatorService {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntryTotal($person) {
        $query = $this->em->createQuery(<<<DQL
            SELECT SUM(e.value)
            FROM DK\CalculatorBundle\Tests\Entity\Entry e JOIN e.person p
            WHERE p=:person
DQL
        )->setParameter("person", $person);
        return (float)$query->getSingleScalarResult();
    }

}