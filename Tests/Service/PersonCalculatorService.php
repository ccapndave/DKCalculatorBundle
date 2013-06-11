<?php
namespace DK\CalculatorBundle\Tests\Service;

use Doctrine\ORM\EntityManager;

class PersonCalculatorService {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntryTotal($person) {
        return 100;
    }

}