<?php
namespace DK\CalculatorBundle\Tests;

use DK\CalculatorBundle\Tests\Entity\Person;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\IndexedReader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use DoctrineExtensions\PHPUnit\Event\EntityManagerEventArgs;
use DoctrineExtensions\PHPUnit\OrmTestCase;

class SchemaSetupListener extends EventManager {

    public function preTestSetUp(EntityManagerEventArgs $eventArgs) {
        $em = $eventArgs->getEntityManager();

        $schemaTool = new SchemaTool($em);

        $cmf = $em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
    }

}

class CalculatorTest extends OrmTestCase {

    protected $kernel;

    protected function get($id) {
        return $this->kernel->getContainer()->get($id);
    }

    public function __construct() {
        require_once(__DIR__."/Functional/app/AppKernel.php");
        $this->kernel = new \AppKernel("test", true);
        $this->kernel->boot();
    }

    protected function createEntityManager() {
        $em = $this->get("doctrine.orm.entity_manager");
        $em->getEventManager()->addEventListener(array("preTestSetUp"), new SchemaSetupListener());
        return $em;
    }

    protected function getDataSet() {
        return new \PHPUnit_Extensions_Database_DataSet_DefaultDataSet();
    }

    public function testHelloWorld() {
        /*$person = new Person();
        $this->getEntityManager()->persist($person);
        $this->getEntityManager()->flush();*/
    }

}