<?php

namespace DK\CalculatorBundle\Metadata\Driver;

use DK\CalculatorBundle\Metadata\PropertyMetadata;
use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;

class AnnotationDriver implements DriverInterface {

    private $reader;

    public function __construct(Reader $reader) {
        $this->reader = $reader;
    }

    public function loadMetadataForClass(\ReflectionClass $class) {
        $classMetadata = new MergeableClassMetadata($class->getName());

        foreach ($class->getProperties() as $reflectionProperty) {
            $propertyMetadata = new PropertyMetadata($class->getName(), $reflectionProperty->getName());

            $annotation = $this->reader->getPropertyAnnotation($reflectionProperty, 'DK\CalculatorBundle\Annotation\Calculator');
            if ($annotation !== null) $propertyMetadata->calculator = $annotation;

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }

}
