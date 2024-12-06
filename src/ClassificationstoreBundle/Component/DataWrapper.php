<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Component;

/**
 * Class DataWrapper
 * @package Mousset\ClassificationstoreBundle\Component
 */
class DataWrapper
{
    /**
     * @var array
     */
    private $data;

    /**
     * DataWrapper constructor.
     * @param array $csvData
     */
    public function __construct(array $csvData)
    {
        $this->data = CsvParser::csvToArray($csvData);
    }

    /**
     * @param string $attributeName
     * @param string|null $defaultValue
     * @return null|string
     */
    public function get(string $attributeName, string $defaultValue = null): ?string
    {
        if (!isset($this->data[$attributeName])) {
            return $defaultValue;
        }

        return $this->data[$attributeName];
    }

    /**
     * @return array
     */
    public function getAllAttributes(): array
    {
        return $this->data;
    }
}
