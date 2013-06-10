<?php
namespace DK\CalculatorBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use DK\CalculatorBundle\Annotation as DK;

/**
 * @ORM\Entity
 */
class Person {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    public function setId($value) { $this->id = $value; return $this; }
    public function getId() { return $this->id; }

    /**
     * @ORM\OneToMany(targetEntity="DK\CalculatorBundle\Tests\Entity\Entry", mappedBy="person")
     */
    protected $entries;
    public function addEntry($value) { $value->setPerson($this); $this->entries[] = $value; return $this; }
    public function getEntries() { return $this->entries; }

    /**
     * @DK\Calculator(service="person.calculator", observers="{entries}")
     */
    protected $totalEntries;
    public function getEntryTotal() {
        return $this->entryTotal;
    }

}