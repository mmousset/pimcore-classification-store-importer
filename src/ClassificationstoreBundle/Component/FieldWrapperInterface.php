<?php
/**
 * @category    ClassificationstoreBundle
 * @date        19/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Component;

use Pimcore\Model\DataObject\Concrete;

/**
 * Interface FieldWrapperInterface
 * @package Divante\ClassificationstoreBundle\Service
 */
interface FieldWrapperInterface
{
    /**
     * Initiates wrapper
     *
     * @param Concrete $object
     * @param string $fieldName
     * @param null|string $language
     * @throws \Exception
     */
    public function setUp(Concrete $object, string $fieldName, string $language = null): void;

    /**
     * Assigns value for proper group, key and language
     *
     * @param string $groupName
     * @param string $keyName
     * @param mixed $value
     */
    public function set(string $groupName, string $keyName, $value): void;

    /**
     * @param string $groupName
     * @param string $keyName
     * @return mixed
     * @throws \Exception
     */
    public function get(string $groupName, string $keyName);

    /**
     * If the key is of type select or multiselect, it adds the value to the list of current values
     *
     * @param string $groupName
     * @param string $keyName
     * @param mixed $value
     * @throws \Exception
     */
    public function add(string $groupName, string $keyName, $value): void;

    /**
     * If the key is of type select or multiselect, it removes the value from the list of current values
     *
     * @param string $groupName
     * @param string $keyName
     * @param mixed $value
     * @throws \Exception
     */
    public function remove(string $groupName, string $keyName, $value): void;

    /**
     * Returns all keys values. Returns array of the form: [name1=>value1, name2=>value2]
     *
     * @param string $groupName
     * @return array
     * @throws \Exception
     */
    public function getGroupValues(string $groupName): array;

    /**
     * Add group to field
     *
     * @param string $groupName
     * @throws \Exception
     */
    public function addGroup(string $groupName): void;

    /**
     * Remove group from field
     *
     * @param string $groupName
     * @throws \Exception
     */
    public function removeGroup(string $groupName): void;

    /**
     * Returns all keys values. Returns array of the form: [name1=>value1, name2=>value2, ...]
     *
     * @return array
     * @throws \Exception
     */
    public function getAllValues(): array;
}
