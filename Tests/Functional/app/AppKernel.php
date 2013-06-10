<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {

    /**
     * @return array
     */
    public function registerBundles() {
        $bundles = array();

        if (in_array($this->getEnvironment(), array('test'))) {
            $bundles[] = new Symfony\Bundle\FrameworkBundle\FrameworkBundle();
            $bundles[] =  new Doctrine\Bundle\DoctrineBundle\DoctrineBundle();
            $bundles[] = new DK\CalculatorBundle\DKCalculatorBundle();
            /*new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Zalas\Bundle\DemoBundle\ZalasDemoBundle()*/
        }

        return $bundles;
    }

    /**
     * @return null
     */
    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir() {
        return sys_get_temp_dir().'/DKCalculatorBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir() {
        return sys_get_temp_dir().'/DKCalculatorBundle/logs';
    }

}