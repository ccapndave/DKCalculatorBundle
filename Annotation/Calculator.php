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

    /** @var string */
    public $service;

    /** @var array */
    public $observes;

    public function __construct($options) {
        if (!isset($options['class']) && !isset($options['service']))
            throw new \InvalidArgumentException("Calculator annotation requires 'class' or 'service'");

        if (isset($options['class'])) $this->class = $options['class'];
        if (isset($options['service'])) $this->service = $options['service'];
        if (isset($options['observes'])) $this->observes = $options['observes'];
    }

}
