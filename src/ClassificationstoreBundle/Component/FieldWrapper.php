<?php
/**
 * @category    ClassificationstoreBundle
 * @date        19/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Component;

use Mousset\ClassificationstoreBundle\Repository\CollectionRepository;
use Mousset\ClassificationstoreBundle\Repository\GroupRepository;
use Mousset\ClassificationstoreBundle\Repository\KeyRepository;
use Mousset\ClassificationstoreBundle\Repository\StoreRepository;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\Classificationstore;
use Pimcore\Model\DataObject\Classificationstore\StoreConfig;
use Pimcore\Model\DataObject\Concrete;

/**
 * Class FieldWrapper
 * @package Mousset\ClassificationstoreBundle\Service
 */
class FieldWrapper implements FieldWrapperInterface
{
    /**
     * @var Classificationstore
     */
    private $storeField;

    /**
     * @var StoreConfig
     */
    private $storeConfig;

    /**
     * @var null|string
     */
    private $language;

    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @var CollectionRepository
     */
    private $collectionRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var KeyRepository
     */
    private $keyRepository;

    /**
     * FieldWrapper constructor.
     *
     * @param StoreRepository $storeRepository
     * @param CollectionRepository $collectionRepository
     * @param GroupRepository $groupRepository
     * @param KeyRepository $keyRepository
     */
    public function __construct(
        StoreRepository      $storeRepository,
        CollectionRepository $collectionRepository,
        GroupRepository      $groupRepository,
        KeyRepository        $keyRepository
    ) {
        $this->storeRepository      = $storeRepository;
        $this->collectionRepository = $collectionRepository;
        $this->groupRepository      = $groupRepository;
        $this->keyRepository        = $keyRepository;

        $this->storeConfig = null;
        $this->language = null;
    }

    /**
     * Initiates wrapper
     *
     * @param Concrete $object
     * @param string $fieldName
     * @param null|string $language
     * @throws \Exception
     */
    public function setUp(Concrete $object, string $fieldName, string $language = null): void
    {
        $getter = 'get' . ucfirst($fieldName);
        if (!method_exists($object, $getter)) {
            throw new \Exception("Missing field $fieldName in object");
        }

        /*
        $type = $object->getClass()->getFieldDefinition($fieldName)->getColumnType();
        if ('classificationstore' !== $type) {
            throw new \Exception("Field $fieldName expected to be Classificationstore, got: $type");
        }
        */

        $storeField = $object->$getter();
        if (!($storeField instanceof Classificationstore)) {
            $storeField = new Classificationstore();
        }
        $this->storeField = $storeField;

        $storeId = $object->getClass()->getFieldDefinition($fieldName)->storeId;
        /** @var Data $data */
        $this->storeConfig = $this->storeRepository->getById($storeId);

        $this->language = $language;
    }

    /**
     * Assigns value for proper group, key and language
     *
     * @param string $groupName
     * @param string $keyName
     * @param mixed $value
     * @throws \Exception
     */
    public function set(string $groupName, string $keyName, $value): void
    {
        $this->checkIsSetUp();
        $storeName = $this->storeConfig->getName();
        $groupId = $this->groupRepository->getByNameOrCreate($groupName, $storeName)->getId();
        $keyId = $this->keyRepository->getByNameOrCreate($keyName, $storeName)->getId();
        $this->storeField->setLocalizedKeyValue($groupId, $keyId, $value, $this->language);
    }

    /**
     * @param string $groupName
     * @param string $keyName
     * @return mixed
     * @throws \Exception
     */
    public function get(string $groupName, string $keyName)
    {
        $this->checkIsSetUp();
        $storeName = $this->storeConfig->getName();
        $groupId = $this->groupRepository->getByNameOrCreate($groupName, $storeName)->getId();
        $keyId = $this->keyRepository->getByNameOrCreate($keyName, $storeName)->getId();
        if ($this->language) {
            $value = $this->storeField->getLocalizedKeyValue($groupId, $keyId, $this->language);
        } else {
            $value = $this->storeField->getLocalizedKeyValue($groupId, $keyId);
        }

        return $value;
    }

    /**
     * If the key is of type select or multiselect, it adds the value to the list of current values
     *
     * @param string $groupName
     * @param string $keyName
     * @param mixed $value
     * @throws \Exception
     */
    public function add(string $groupName, string $keyName, $value): void
    {
        $this->checkIsSetUp();
        $data = $this->get($groupName, $keyName);
        $array = [];
        if ($data) {
            $array = explode(',', $data);
        }
        if (!in_array($value, $array)) {
            $array[] = $value;
            $data = implode(',', $array);
            $this->set($groupName, $keyName, $data);
        }
    }

    /**
     * If the key is of type select or multiselect, it removes the value from the list of current values
     *
     * @param string $groupName
     * @param string $keyName
     * @param mixed $value
     * @throws \Exception
     */
    public function remove(string $groupName, string $keyName, $value): void
    {
        $this->checkIsSetUp();
        $data = $this->get($groupName, $keyName);
        $array = [];
        if ($data) {
            $array = explode(',', $data);
        }

        if (in_array($value, $array)) {
            unset($array[array_search($value, $array)]);
            $data = implode(',', $array);
            $this->set($groupName, $keyName, $data);
        }
    }

    /**
     * Returns all keys values. Returns array of the form: [name1=>value1, name2=>value2]
     *
     * @param string $groupName
     * @return array
     * @throws \Exception
     */
    public function getGroupValues(string $groupName): array
    {
        $this->checkIsSetUp();
        $values = [];
        $storeName = $this->storeConfig->getName();
        $groupConfig = $this->groupRepository->getByNameOrCreate($groupName, $storeName);
        $groupId = $groupConfig->getId();

        $items = $this->storeField->getItems();
        if (!is_array($items)) {
            return $values;
        }

        foreach ($items as $grId => $groupValues) {
            if ($groupId !== $grId) {
                continue;
            }
            foreach ($groupValues as $keyId => $value) {
                $keyName = $this->keyRepository->getById($keyId)->getName();
                $values[$keyName] = $this->get($groupName, $keyName);
            }
            break;
        }

        return $values;
    }

    /**
     * Returns all keys values. Returns array of the form: [name1=>value1, name2=>value2, ...]
     *
     * @return array
     * @throws \Exception
     */
    public function getAllValues(): array
    {
        $this->checkIsSetUp();

        $values = [];
        $items = $this->storeField->getItems();
        if (!is_array($items)) {
            return $values;
        }

        foreach ($items as $groupId => $groupValues) {
            $groupName = $this->groupRepository->getById($groupId)->getName();
            $values = array_merge($values, $this->getGroupValues($groupName));
        }

        return $values;
    }

    /**
     * Add group to field
     *
     * @param string $groupName
     * @throws \Exception
     */
    public function addGroup(string $groupName): void
    {
        $this->checkIsSetUp();
        $storeName = $this->storeConfig->getName();
        $groupId = $this->groupRepository->getByNameOrCreate($groupName, $storeName)->getId();
        $activeGroups = $this->storeField->getActiveGroups();
        $activeGroups[$groupId] = true;
        $this->storeField->setActiveGroups($activeGroups);
    }

    /**
     * Remove group from field
     *
     * @param string $groupName
     * @throws \Exception
     */
    public function removeGroup(string $groupName): void
    {
        $this->checkIsSetUp();
        $storeName = $this->storeConfig->getName();
        $groupId = $this->groupRepository->getByNameOrCreate($groupName, $storeName)->getId();
        $activeGroups = $this->storeField->getActiveGroups();
        unset($activeGroups[$groupId]);
        $this->storeField->setActiveGroups($activeGroups);
    }

    /**
     * @throws \Exception
     */
    private function checkIsSetUp(): void
    {
        if (!$this->storeConfig) {
            throw new \Exception("Not initiated");
        }
    }
}
