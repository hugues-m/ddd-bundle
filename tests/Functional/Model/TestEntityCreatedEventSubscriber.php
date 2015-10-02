<?php

namespace HMLB\DDDBundle\Tests\Functional\Model;

use SimpleBus\Message\Bus\MessageBus;

class TestEntityCreatedEventSubscriber
{
    private $commandBus;
    public $eventHandled = false;

    public function __construct(MessageBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function notify()
    {
        $this->eventHandled = true;

        $this->commandBus->handle(new SomeOtherTestCommand());
    }
}
