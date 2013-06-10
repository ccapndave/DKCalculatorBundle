<?php
namespace DK\CalculatorBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use DK\CalculatorBundle\Annotation as DK;

/**
 * @ORM\Entity
 */
class Entry {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    public function setId($value) { $this->id = $value; return $this; }
    public function getId() { return $this->id; }

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $value;
    public function setValue($value) { $this->value = $value; return $this; }
    public function getValue() { return $this->value; }

    /** @ORM\ManyToOne(targetEntity="DK\CalculatorBundle\Tests\Entity\Person", inversedBy="entries") */
    protected $person;
    public function setPerson($value) { $this->person = $value; return $this; }
    public function getPerson() { return $this->person; }

}