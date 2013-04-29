<?php

namespace DK\CalculatorBundle\Annotation;

/**
 * Represents a @Calculator annotation.
 *
 * @Annotation
 * @Target("PROPERTY")
 * @author Dave Keen
 */
final class Calculator {

    /** @var string */
    public $class;

    public function __construct($options) {
        if (!isset($options['class']))
            throw new \InvalidArgumentException("Calculator annotation requires 'class'");

        $this->class = $options['class'];
    }

}
