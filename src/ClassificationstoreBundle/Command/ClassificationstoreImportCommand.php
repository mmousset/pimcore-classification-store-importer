<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Command;

use Mousset\ClassificationstoreBundle\Component\CsvParser;
use Mousset\ClassificationstoreBundle\Component\DataWrapper;
use Mousset\ClassificationstoreBundle\Import\Importer;
use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ClassificationstoreImportCommand
 * @package Mousset\ClassificationstoreBundle\Command
 */
class ClassificationstoreImportCommand extends Command
{

    public function __construct(Importer $importer, SerializerInterface $serializer)
    {
        $this->importer = $importer;
        $this->serializer = $serializer;
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('mousset:classificationstore:import')
            ->setDescription('Import definition of Classificationstore from CSV file')
            ->addOption('file', 'f', InputArgument::OPTIONAL, 'CSV file name with classificationstore definition')
            ->addOption(
                'asset',
                'a',
                InputArgument::OPTIONAL,
                'Path in assets to CSV file name with classificationstore definition'
            )
            ->addOption('type', 't', InputArgument::OPTIONAL, 'Type of asset you want to import (store, group, key, ...)', NULL)
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
            if (!$assetObj instanceof Asset) {
                $output->writeln("<info>Asset doesn't exist</info>");
                return 0;
            }
            $file = PIMCORE_ASSET_DIRECTORY . $assetObj->getFullPath();
        } else {
            $file = $input->getOption('file');
        }

        $type = $input->getOption('type');
        $delimiter = $input->getOption('delimiter');
        $enclosure = $input->getOption('enclosure');

        if (!file_exists($file)) {
            $output->writeln("<info>File " . $file . " doesn't exist</info>");
            return 0;
        }

        // for count only
        $csvData = file_get_contents($file);
        $data = $this->serializer->decode($csvData, 'csv', ['csv_delimiter' => $delimiter, 'csv_enclosure' => $enclosure]);
        $count = count($data);
        $output->writeln('<info>Importing `' . $count . '` lines.</info>');
        // for count only - end

        $progressBar = new ProgressBar($output, $count);
        $progressBar->start();
        $output->writeln('');

        if ($type == 'Store') {
            executeStoreImport();
        } else if ($type == 'Key') {
            $header = fgetcsv($file);
            while ($row = fgetcsv($file)) {
                $row = array_combine($header, $row);
                $data = new DataWrapper($row);
                executeKeyImport($data);
            }
        } else {
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
                    $item = $this->importer->importItem($data);
                    $success++;
                    $output->writeln("imported: " . $item->getItemType() . " name: " . $item->getName());
                } catch (\Exception $exception) {
                    $output->writeln("import failed: " . $exception->getMessage());
                }

                $progressBar->advance();
            }
        }

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('<info>Processed: ' . $counter . ' items.</info>');
        $output->writeln('<info>Success: ' . $success . ' items.</info>');
        if ($counter > $success) {
            $output->writeln('<error>Failed: ' . ($counter - $success) . ' items.</error>');
        }

        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Exception
     */
    protected function executeStoreImport()
    {

    }

    /**
     * @param mixed  $data
     *
     * @return int|null|void
     * @throws \Exception
     */
    protected function executeKeyImport(mixed $data)
    {
        $item = $this->importer->importKey($data);

    }

}
