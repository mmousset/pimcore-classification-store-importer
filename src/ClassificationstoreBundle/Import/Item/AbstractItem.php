<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Item;

use Divante\ClassificationstoreBundle\Component\DataWrapper;
use Divante\ClassificationstoreBundle\Repository\KeyRepository;
use Divante\ClassificationstoreBundle\Repository\StoreRepository;
use Divante\ClassificationstoreBundle\Repository\CollectionRepository;
use Divante\ClassificationstoreBundle\Repository\GroupRepository;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class AbstractItem
 * @package Divante\ClassificationstoreBundle\Import\Item
 */
abstract class AbstractItem implements ItemInterface
{
    /**
     * @var DataWrapper
     */
    private $data;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @var KeyRepository
     */
    protected $keyRepository;

    /**
     * @param DataWrapper $data
     * @throws \Exception
     */
    public function __construct(DataWrapper $data)
    {
        $this->data = $data;
        if (!$this->getName()) {
            throw new \Exception("Missing item name");
        }
        $this->storeRepository      = \Pimcore::getContainer()->get(StoreRepository::class);
        $this->collectionRepository = \Pimcore::getContainer()->get(CollectionRepository::class);
        $this->groupRepository      = \Pimcore::getContainer()->get(GroupRepository::class);
        $this->keyRepository        = \Pimcore::getContainer()->get(KeyRepository::class);
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->data->get(ItemInterface::ITEM);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->data->get(ItemInterface::NAME);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->data->get(ItemInterface::TITLE, '');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->data->get(ItemInterface::DESCRIPTION, '');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->data->get(ItemInterface::TYPE);
    }

    /**
     * @return string
     */
    public function getStore(): string
    {
        return $this->data->get(ItemInterface::STORE);
    }

    /**
     * @param string $attributeName
     * @return null|string
     */
    public function get(string $attributeName): ?string
    {
        return $this->data->get($attributeName);
    }

    /**
     * @return array
     */
    public function getAllAttributes(): array
    {
        return $this->data->getAllAttributes();
    }
}
