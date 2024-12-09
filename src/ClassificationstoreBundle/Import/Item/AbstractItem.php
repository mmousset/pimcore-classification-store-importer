<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item;

use Mousset\ClassificationstoreBundle\Component\DataWrapper;
use Mousset\ClassificationstoreBundle\Repository\KeyRepository;
use Mousset\ClassificationstoreBundle\Repository\StoreRepository;
use Mousset\ClassificationstoreBundle\Repository\CollectionRepository;
use Mousset\ClassificationstoreBundle\Repository\GroupRepository;
use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class AbstractItem
 * @package Mousset\ClassificationstoreBundle\Import\Item
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
        $this->UnitRepository       = \Pimcore::getContainer()->get(Unit::class);
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->data->get(Constants::ITEM);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->data->get(Constants::NAME);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->data->get(Constants::TITLE, '');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->data->get(Constants::DESCRIPTION, '');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->data->get(Constants::TYPE);
    }

    /**
     * @return string
     */
    public function getStore(): string
    {
        return $this->data->get(Constants::STORE);
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
