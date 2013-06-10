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

    protected function createEntityManager() {
        /*$dbParams = array(
            'driver'   => 'pdo_sqlite',
            'dbname'   => 'test.db'
        );*/

        $dbParams = array(
            'driver' => 'pdo_mysql',
            'dbname' => 'dkcalculator_test',
            'user' => 'root'
        );

        $paths = array(dirname(__FILE__)."/Entity");

        $eventManager = new EventManager();
        $eventManager->addEventListener(array("preTestSetUp"), new SchemaSetupListener());

        $config = Setup::createAnnotationMetadataConfiguration($paths, true);
        $config->setMetadataDriverImpl(new AnnotationDriver(new IndexedReader(new AnnotationReader()), $paths));

        $em = EntityManager::create($dbParams, $config, $eventManager);
        return $em;
    }

    protected function getDataSet() {
        return new \PHPUnit_Extensions_Database_DataSet_DefaultDataSet();
    }

    public function testHelloWorld() {
        $person = new Person();
        $this->getEntityManager()->persist($person);
        $this->getEntityManager()->flush();
    }

}