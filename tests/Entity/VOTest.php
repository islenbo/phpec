<?php

namespace PHPEc\Tests\Entity;

use ArrayObject;
use PHPEc\Entity\VO;
use PHPEc\Tests\data\Entity\JsonSerializeData;
use PHPUnit\Framework\TestCase;

class VOTest extends TestCase
{

    /**
     * Test getArrayableItems()
     *
     * @dataProvider dataProviderVOData
     * @param array $data
     */
    public function testGetArrayableItems(array $data)
    {
        $vo = new VO($data);
        $this->assertEquals($data, $vo->toArray());

        $vo = new VO($vo);
        $this->assertEquals($data, $vo->toArray());

        $vo = new VO(new JsonSerializeData($data));
        $this->assertEquals($data, $vo->toArray());

        $vo = new VO(new ArrayObject($data));
        $this->assertEquals($data, $vo->toArray());
    }

    /**
     * Data Provider VO Data
     * @return array
     */
    public function dataProviderVOData(): array
    {
        $data1 = [
            'name' => '张三',
            'age' => 30,
        ];
        return [
            [$data1],
        ];
    }
}
