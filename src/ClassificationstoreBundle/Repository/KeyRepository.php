<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Repository;

use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class KeyRepository
 * @package Divante\ClassificationstoreBundle\Repository
 */
class KeyRepository
{
    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * KeyRepository constructor.
     * @param StoreRepository $storeRepository
     */
    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * @param string $name
     * @param string $storeName
     * @return KeyConfig
     */
    public function getByNameOrCreate(string $name, string $storeName): KeyConfig
    {
        $storeConfig = $this->storeRepository->getByNameOrCreate($storeName);
        $keyConfig = KeyConfig::getByName($name, $storeConfig->getId());
        if (!$keyConfig) {
            $definition = [
                'fieldtype' => 'input',
                'name' => $name,
                'title' => $name,
                'datatype' => 'data'
            ];

            $keyConfig = new KeyConfig();
            $keyConfig->setName($name);
            $keyConfig->setTitle($name);
            $keyConfig->setType('input');
            $keyConfig->setStoreId($storeConfig->getId());
            $keyConfig->setEnabled(1);
            $keyConfig->setDefinition(json_encode($definition));
            $keyConfig->save();
        }

        return $keyConfig;
    }

    /**
     * @param int $keyId
     * @return KeyConfig
     */
    public function getById(int $keyId): KeyConfig
    {
        return KeyConfig::getById($keyId);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $list = new KeyConfig\Listing();
        return $list->load();
    }

    /**
     *
     */
    public function deleteAll()
    {
        foreach ($this->getAll() as $keyConfig) {
            $keyConfig->delete();
        }
    }
}
