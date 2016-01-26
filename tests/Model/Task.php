<?php

namespace HMLB\DDDBundle\Tests\Model;

use HMLB\DDD\Entity\Identity;
use HMLB\DDDBundle\Tests\Message\SomethingImportantHappened;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * Task.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class Task implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    /**
     * @var Identity
     */
    private $id;

    /**
     * @var string
     */
    private $goal;

    /**
     * Task constructor.
     *
     * @param string $goal
     */
    public function __construct(string $goal)
    {
        $this->id = new Identity();
        $this->goal = $goal;
    }

    public function execute()
    {
        $notice = sprintf('%sNow "%s"%s', PHP_EOL, $this->goal, PHP_EOL);
        echo $notice;
        $this->record(new SomethingImportantHappened($this->goal));
    }
}
