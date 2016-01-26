<?php

namespace HMLB\DDDBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use HMLB\DDD\Message\MessageInterface;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

/**
 * CollectsEventsFromEntities.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class CollectsEventsFromEntities implements EventSubscriber
{
    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var MessageInterface[]
     */
    private $collectedEvents = [];

    /**
     * CollectsEventsFromEntities constructor.
     *
     * @param LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            'prePersist',
            'preUpdate',
            'postPersist',
            'postUpdate',
            'postRemove',
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: prePersist listened');
        $this->collectEventsFromEntity($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
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

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: preUpdate listened');
        $this->collectEventsFromEntity($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: postPersist listened');
        $this->collectEventsFromEntity($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: postUpdate listened');
        $this->collectEventsFromEntity($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postRemove(LifecycleEventArgs $event)
    {
        $this->log->debug('CollectsEventsFromEntities: postRemove listened');
        $this->collectEventsFromEntity($event);
    }

    /**
     * @return MessageInterface[]
     */
    public function recordedMessages(): array
    {
        $this->log->debug('CollectsEventsFromEntities giving it\'s '.count($this->collectedEvents).' events');

        return $this->collectedEvents;
    }

    /**
     * Reset recored messages.
     */
    public function eraseMessages()
    {
        $this->log->debug('CollectsEventsFromEntities erasing events');
        $this->collectedEvents = [];
    }
}
