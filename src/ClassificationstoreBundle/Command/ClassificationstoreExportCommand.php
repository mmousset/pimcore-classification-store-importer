<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Command;

use Divante\ClassificationstoreBundle\Export\Exporter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClassificationstoreExportCommand
 * @package Divante\ClassificationstoreBundle\Command
 */
class ClassificationstoreExportCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('divante:classificationstore:export')
            ->setDescription('Export definition of Classificationstore to CSV file')
            ->addOption('file', 'f', InputArgument::OPTIONAL, 'CSV file name')
            ->addOption('delimiter', 'd', InputArgument::OPTIONAL, 'CSV delimiter', ';')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('file');
        $delimiter = $input->getOption('delimiter');

        /** @var Exporter $exporter */
        $exporter = $this->getContainer()->get(Exporter::class);

        $csv = $exporter->getCsv($delimiter);
        file_put_contents($file, $csv);

        // @TODO - remove 2 lines below
        $output->writeln('');
        $output->writeln($csv);
    }
}
