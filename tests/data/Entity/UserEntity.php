<?php

namespace PHPEc\Tests\data\Entity;

use PHPEc\Entity\Entity;

/**
 * @property string $name
 * @property int $age
 * @property float $score
 * @property bool $isVip
 * @property string[] $hobby
 * @property UserEntity $father
 * @property UserEntity $mother
 * @property UserEntity[] $kids
 * @property UserEntity[] $friends
 */
class UserEntity extends Entity
{

    protected function propertyType(): array
    {
        return [
            'name' => 'string',
            'age' => 'int',
            'score' => 'float',
            'isVip' => 'bool',
            'hobby' => 'string[]',
            'father' => UserEntity::class,
            'mother' => UserEntity::class,
            'kids' => UserEntity::class . '[]',
            'friends' => UserEntity::class . '[]',
        ];
    }
}
