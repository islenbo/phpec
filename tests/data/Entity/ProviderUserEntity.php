<?php

namespace PHPEc\Tests\data\Entity;

class ProviderUserEntity
{

    public static function provider(): array
    {
        $self = new UserEntity();
        $self->name = '自己';
        $self->age = 30;

        $friend1 = new UserEntity();
        $friend1->name = '朋友1';
        $friend1->age = 30;

        $friend2 = new UserEntity();
        $friend2->name = '朋友2';
        $friend2->age = 30;

        $mother = new UserEntity();
        $mother->name = '母亲';
        $mother->age = 60;

        $father = new UserEntity();
        $father->name = '父亲';
        $father->age = 60;

        $child1 = new UserEntity();
        $child1->name = '儿子1';
        $child1->age = 10;

        $self->father = $father;
        $self->mother = $mother;
        $self->friends = [$friend1, $friend2];
        $self->kids = [$child1];

        return [
            [$self, $child1],
            [$friend1, $friend2],
            [$father, $mother],
        ];
    }
}
