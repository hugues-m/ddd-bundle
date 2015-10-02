<?php

namespace HMLB\DDDBundle\Tests\Functional\Model;

class SomeOtherEventSubscriber
{
    public $eventHandled = false;

    public function notify()
    {
        $this->eventHandled = true;
    }
}
