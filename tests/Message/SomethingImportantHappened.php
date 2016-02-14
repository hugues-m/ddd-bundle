<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Tests\Message;

use HMLB\DDD\Message\Event\PersistentEvent;

/**
 * Test event.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class SomethingImportantHappened extends PersistentEvent
{
    /**
     * The thing that happened.
     *
     * @var string
     */
    private $thing;

    /**
     * SomethingImportantHappened constructor.
     *
     * @param string $thing
     */
    public function __construct(string $thing)
    {
        $this->thing = $thing;
    }

    /**
     * @return string
     */
    public function getThing(): string
    {
        return $this->thing;
    }
}
