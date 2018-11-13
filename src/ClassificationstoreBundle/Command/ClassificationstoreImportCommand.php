<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Command;

use Divante\ClassificationstoreBundle\Component\CsvParser;
use Divante\ClassificationstoreBundle\Component\DataWrapper;
use Divante\ClassificationstoreBundle\Import\Importer;
use Divante\ClassificationstoreBundle\Constants;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;
use Pimcore\Model\Asset;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClassificationstoreImportCommand
 * @package Divante\ClassificationstoreBundle\Command
 */
class ClassificationstoreImportCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('divante:classificationstore:import')
            ->setDescription('Import definition of Classificationstore from CSV file')
            ->addOption('file', 'f', InputArgument::OPTIONAL, 'CSV file name with classificationstore definition')
            ->addOption(
                'asset',
                'a',
                InputArgument::OPTIONAL,
                'Path in assets to CSV file name with classificationstore definition'
            )
            ->addOption('delimiter', 'd', InputArgument::OPTIONAL, 'CSV delimiter', ';')
            ->addOption('enclosure', 'c', InputArgument::OPTIONAL, 'Field enclosure', '"')
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
        $asset = $input->getOption('asset');
        if ($asset) {
            $assetObj = Asset::getByPath($asset);
            $file = PIMCORE_ASSET_DIRECTORY . $assetObj->getFullPath();
        } else {
            $file = $input->getOption('file');
        }

        $delimiter = $input->getOption('delimiter');
        $enclosure = $input->getOption('enclosure');

        // for count only
        $csvData = file_get_contents($file);
        $serializer = $this->getContainer()->get('serializer');
        $data = $serializer->decode($csvData, 'csv', ['csv_delimiter' => $delimiter, 'csv_enclosure' => $enclosure]);
        $count = count($data);
        $output->writeln('<info>Importing `' . $count . '` lines.</info>');
        // for count only - end

        $progressBar = new ProgressBar($output, $count);
        $progressBar->start();

        /** @var Importer $importer */
        $importer = $this->getContainer()->get(Importer::class);

        $file = new \SplFileObject($file);
        $file->setFlags(\SplFileObject::READ_CSV);
        $file->setCsvControl($delimiter, $enclosure);
        $counter = 0;
        $success = 0;
        foreach ($file as $row) {
            $data = new DataWrapper($row);
            if (!$data->get(Constants::ITEM)) {
                continue;
            }

            $counter++;
            try {
                $item = $importer->importItem($data);
                $success++;
                $output->writeln("imported: " . $item->getItemType() . " name: " . $item->getName());
            } catch (\Exception $e) {
                $output->writeln("import failed: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('<info>Processed: ' . $counter . ' items.</info>');
        $output->writeln('<info>Success: ' . $success . ' items.</info>');
        if ($counter > $success) {
            $output->writeln('<error>Failed: ' . ($counter - $success) . ' items.</error>');
        }
    }
}
