<?php

namespace DK\CalculatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName('dk:calculator:refresh')
            ->setDescription('Recalculate all cached calculated fields');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $calculator = $this->getContainer()->get("dk_calculator.calculator");

        // Go through each entity retrieving each from the database and recalulating their calculated properties
        foreach ($em->getMetadataFactory()->getAllMetadata() as $metadata) {
            $output->write("Calculating entities for ".$metadata->name.": ");
            try {
                $entities = $em->getRepository($metadata->name)->findAll();
                foreach ($entities as $entity) {
                    $calculator->calculate($entity);
                    $output->write(".");
                }

                $output->writeln(" done");
            } catch (\Exception $e) {
                // Sometimes there are DQL errors which we want to log, but ignore
                $output->writeln($e->getMessage()."\n\n");
            }
        }
    }

}
