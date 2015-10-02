<?php

namespace HMLB\DDDBundle\Tests\Functional\Model;

use Doctrine\ORM\EntityManager;
use HMLB\DDDBundle\Tests\Functional\Model\Entity\TestEntity;

class TestCommandHandler
{
    public $commandHandled = false;

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle()
    {
        $this->commandHandled = true;

        $entity = new TestEntity();

        $this->entityManager->persist($entity);

        // flush should be called inside the TransactionalCommandBus
    }
}
