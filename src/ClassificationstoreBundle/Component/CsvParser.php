<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Component;

/**
 * Class CsvParser
 * @package Divante\ClassificationstoreBundle\Component
 */
class CsvParser
{
    /**
     * @param array $csvData
     * @return array
     */
    public static function csvToArray(array $csvData): array
    {
        $data = [];
        $len = count($csvData);
        for ($iter = 0; $iter < $len; $iter+=2) {
            $data[$csvData[$iter]] = $csvData[$iter + 1];
        }

        return $data;
    }

    /**
     * @param array $arrayData
     * @param string $enclosure
     * @return array
     */
    public static function arrayToCsv(array $arrayData, string $enclosure): array
    {
        $data = [];
        foreach ($arrayData as $name => $value) {
            $data[] = $enclosure . $name . $enclosure;
            $data[] = $enclosure . $value . $enclosure;
        }

        return $data;
    }
}
