<?php

namespace PHPEc\Tests\data\Entity;

use PHPEc\Entity\Entity;

/**
 * @property string $name
 * @property int $age
 * @property float $score
 * @property bool $isVip
 * @property UserEntity $father
 * @property UserEntity $mother
 * @property UserEntity[] $kids
 * @property UserEntity[] $friends
 */
class UserEntity extends Entity
{

    protected $propertyType = [
        'father' => UserEntity::class,
        'kids' => UserEntity::class . '[]',
    ];
}
