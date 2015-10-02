<?php

namespace HMLB\DDDBundle\Tests\Functional\Model;

use SimpleBus\Message\Name\NamedMessage;

class SomeOtherEvent implements NamedMessage
{
    public static function messageName()
    {
        return 'some_other_event';
    }
}
