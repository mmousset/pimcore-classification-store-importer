<?php
/**
 * @category    ClassificationstoreBundle
 * @date        12/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Component;

use Divante\ClassificationstoreBundle\Component\CsvParser;
use Divante\ClassificationstoreBundle\Component\DataCleaner;
use Pimcore\Test\KernelTestCase;

class DataCleanerTest extends KernelTestCase
{
    public function test_removeEmptyData()
    {
        $inData = [
            'name1' => 'value1',
            'name2' => '',
            'name3' => 'value3'
        ];

        $outData = DataCleaner::removeEmptyData($inData);

        $this->assertTrue(is_array($outData));
        $this->assertEquals(2, count($outData));

        $this->assertTrue(isset($outData['name1']));
        $this->assertEquals('value1', $outData['name1']);

        $this->assertTrue(isset($outData['name3']));
        $this->assertEquals('value3', $outData['name3']);
    }
}
