<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Doctrine\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnClassMetadataNotFoundEventArgs;
use Doctrine\ORM\Events;

/**
 * MessagesMappingSubscriber.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class MessagesMappingSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
            Events::onClassMetadataNotFound,
        ];
    }

    public function onClassMetadataNotFound(OnClassMetadataNotFoundEventArgs $event)
    {
    }

    /**
     * @para
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
    }
}
