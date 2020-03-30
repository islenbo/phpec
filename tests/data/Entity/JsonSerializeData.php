<?php

namespace PHPEc\Tests\data\Entity;

use JsonSerializable;

class JsonSerializeData implements JsonSerializable
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
