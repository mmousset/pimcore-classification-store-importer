<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Command;

use Divante\ClassificationstoreBundle\Export\Exporter;
use Pimcore\Model\Asset;
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
            ->addOption('asset', 'a', InputArgument::OPTIONAL, 'Path in assets to CSV file name')
            ->addOption('store', 's', InputArgument::OPTIONAL, 'Name of store (if not used, it exports all stores)', '')
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
        $delimiter = $input->getOption('delimiter');
        $store = $input->getOption('store');

        /** @var Exporter $exporter */
        $exporter = $this->getContainer()->get(Exporter::class);

        $csv = $exporter->getCsv($delimiter, $store);

        $asset = $input->getOption('asset');

        if ($asset) {
            $assetObj = $this->getAssetByPath($asset);
            $assetObj->setData($csv);
            $assetObj->save();
        } else {
            $file = $input->getOption('file');
            file_put_contents($file, $csv);
        }
    }

    /**
     * @param string $path
     * @return Asset
     * @throws \Exception
     */
    private function getAssetByPath(string $path): Asset
    {
        if ($path{0} !== '/') {
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
