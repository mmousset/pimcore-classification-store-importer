<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Command;

use Mousset\ClassificationstoreBundle\Export\Exporter;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClassificationstoreExportCommand
 * @package Mousset\ClassificationstoreBundle\Command
 */
class ClassificationstoreExportCommand extends Command
{

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('Mousset:classificationstore:export')
            ->setDescription('Export definition of Classificationstore to CSV file')
            ->addOption('file', 'f', InputArgument::OPTIONAL, 'CSV file name')
            ->addOption('asset', 'a', InputArgument::OPTIONAL, 'Path in assets to CSV file name')
            ->addOption('store', 's', InputArgument::OPTIONAL, 'Name of store (if not used, it exports all stores)', '')
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
        $delimiter = $input->getOption('delimiter');
        $enclosure = $input->getOption('enclosure');

        $store = $input->getOption('store');

        $csv = $this->exporter->getCsv($delimiter, $enclosure, $store);

        $asset = $input->getOption('asset');

        if ($asset) {
            $assetObj = $this->getAssetByPath($asset);
            $assetObj->setData($csv);
            $assetObj->save();
        } else {
            $file = $input->getOption('file');
            file_put_contents($file, $csv);
        }

        return 0;
    }

    /**
     * @param string $path
     * @return Asset
     * @throws \Exception
     */
    private function getAssetByPath(string $path): Asset
    {
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $asset = Asset::getByPath($path);

        if (!($asset instanceof Asset)) {
            $pos = strrpos($path, '/') + 1;
            $folderPath = substr($path, 0, $pos);
            $assetName = substr($path, $pos);

            $folder = Asset::getByPath($folderPath);
            if (!($folder instanceof Asset\Folder)) {
                $folder = Asset\Service::createFolderByPath($folderPath);
            }
            $asset = Asset::create($folder->getId(), ['filename' => $assetName]);
        }

        return $asset;
    }
}
