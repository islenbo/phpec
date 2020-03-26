<?php

namespace PHPEc\Tests\data\Entity;

use PHPEc\Entity\Entity;

/**
 * @property string $properties
 * @property string $testKey
 */
class PropertyConflictEntity extends Entity
{
    /**
     * @param string $properties
     */
    public function test(string $properties): void
    {
        $this->properties = $properties;
    }

}
