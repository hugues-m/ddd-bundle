<?php

namespace HMLB\DDDBundle\Doctrine\ORM;

use HMLB\DDD\Entity\Identity;
use HMLB\DDD\Message\Event\PersistentEvent;
use HMLB\DDDBundle\Repository\PersistentEventRepository;

/**
 * ORMPersistentEventRepository.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class ORMPersistentEventRepository extends AbstractOrmPersistentMessageRepository implements PersistentEventRepository
{
    /**
     * @param Identity $identity
     *
     * @return PersistentEvent
     */
    public function get(Identity $identity): PersistentEvent
    {
        return $this->em->getRepository(PersistentEvent::class)->find($identity);
    }
}
