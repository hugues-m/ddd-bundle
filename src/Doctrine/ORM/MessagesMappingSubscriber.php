<?php

namespace HMLB\DDDBundle\Doctrine\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnClassMetadataNotFoundEventArgs;
use Doctrine\ORM\Events;

/**
 * MessagesMappingSubscriber.
 *
 * @author Hugues Maignol <hugues.maignol@kitpages.fr>
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
