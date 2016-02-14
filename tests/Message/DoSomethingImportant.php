<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Tests\Message;

use HMLB\DDD\Message\Command\PersistentCommand;

/**
 * A test command.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class DoSomethingImportant extends PersistentCommand
{
    /**
     * The thing that is important.
     *
     * @var string
     */
    private $task;

    /**
     * DoSomethingImportant constructor.
     *
     * @param string $task
     */
    public function __construct(string $task)
    {
        $this->task = $task;
    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }
}
