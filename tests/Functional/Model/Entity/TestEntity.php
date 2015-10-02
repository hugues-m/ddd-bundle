<?php

namespace HMLB\DDDBundle\Tests\Functional\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;
use HMLB\DDDBundle\Tests\Functional\Model\TestEntityCreated;

/**
 * @ORM\Entity
 */
class TestEntity implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    public function __construct()
    {
        $this->record(new TestEntityCreated($this));
    }
}
