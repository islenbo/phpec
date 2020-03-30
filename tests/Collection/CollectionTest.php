<?php

namespace PHPEc\Tests\Collection;

use PHPEc\Collection\Collection;
use PHPEc\Exception\NotSupportedException;
use PHPEc\Tests\data\Entity\UserEntity;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    /**
     * Test offsetSet
     */
    public function testOffsetSet()
    {
        $collection = new Collection(UserEntity::class);
        try {
            $collection[] = new \stdClass();
            $this->assertTrue(false, '类型错误，却没有抛出异常');
        } catch (\Exception $e) {
            $this->assertInstanceOf(NotSupportedException::class, $e);
        }

        $entity1 = new UserEntity();
        $entity2 = new UserEntity();
        $collection->append($entity1)
            ->append($entity2);
        $this->assertEquals(2, count($collection));
        $this->assertEquals($entity1, $collection[0]);
        $this->assertEquals($entity2, $collection[1]);
    }

}
