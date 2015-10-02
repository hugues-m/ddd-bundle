<?php

namespace HMLB\DDDBundle\Tests\Functional\Model;

use SimpleBus\Message\Name\NamedMessage;
use HMLB\DDDBundle\Tests\Functional\Model\Entity\TestEntity;

class TestEntityCreated implements NamedMessage
{
    private $testEntity;

    public function __construct(TestEntity $testEntity)
    {
        $this->testEntity = $testEntity;
    }

    public function getTestEntity()
    {
        return $this->testEntity;
    }

    public static function messageName()
    {
        return 'test_entity_created';
    }
}
