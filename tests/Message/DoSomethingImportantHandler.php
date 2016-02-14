<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Tests\Message;

use Doctrine\Common\Persistence\ObjectManager;
use HMLB\DDD\Message\Command\Command;
use HMLB\DDD\Message\Command\Handler;
use HMLB\DDDBundle\Tests\Model\Task;

/**
 * A test command.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class DoSomethingImportantHandler implements Handler
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * DoSomethingImportantHandler constructor.
     *
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param Command|DoSomethingImportant $command
     */
    public function handle(Command $command)
    {
        $task = new Task($command->getTask());
        $task->execute();
        $this->om->persist($task);
    }
}
