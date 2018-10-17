<?php
/**
 * @category    ClassificationstoreBundle
 * @date        17/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Component;

use Divante\ClassificationstoreBundle\Component\DataWrapper;
use Pimcore\Test\KernelTestCase;

class DataWrapperTest extends KernelTestCase
{
    /**
     * @var DataWrapper
     */
    private $dataWrapper;

    protected function setUp()
    {
        $this->dataWrapper = new DataWrapper(['name1', 'value1', 'name2', 'value2', 'name3', 'value3']);
    }

    public function test_get()
    {
        $this->assertEquals('value1', $this->dataWrapper->get('name1'));
        $this->assertEquals('value1', $this->dataWrapper->get('name1', 'other'));
        $this->assertEquals('other', $this->dataWrapper->get('name4', 'other'));
        $this->assertEquals(null, $this->dataWrapper->get('name4'));
    }

    public function test_getAll()
    {
        $all = $this->dataWrapper->getAllAttributes();
        $this->assertEquals(3, count($all));
        $this->assertEquals('value1', $all['name1']);
    }
}
