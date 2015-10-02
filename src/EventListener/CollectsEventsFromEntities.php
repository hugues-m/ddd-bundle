<?php

namespace HMLB\DDDBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

/**
 * CollectsEventsFromEntities.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class CollectsEventsFromEntities
{
    /**
     * @var LoggerInterface
     */
    protected $log;

    private $collectedEvents = [];

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postPersist',
            'postUpdate',
            'postRemove',
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: prePersist listened');
        $this->collectEventsFromEntity($event);
    }

    private function collectEventsFromEntity(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if ($entity instanceof ContainsRecordedMessages) {
            foreach ($entity->recordedMessages() as $event) {
                $this->log->debug(get_class($event).' was collected');
                $this->collectedEvents[] = $event;
            }
            $entity->eraseMessages();
        }
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: preUpdate listened');
        $this->collectEventsFromEntity($event);
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: postPersist listened');
        $this->collectEventsFromEntity($event);
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: postUpdate listened');
        $this->collectEventsFromEntity($event);
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: postRemove listened');
        $this->collectEventsFromEntity($event);
    }

    public function recordedMessages()
    {
        return $this->collectedEvents;
    }

    public function eraseMessages()
    {
        $this->collectedEvents = [];
    }
}
