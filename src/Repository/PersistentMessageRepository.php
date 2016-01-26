<?php

namespace HMLB\DDDBundle\Repository;

use HMLB\DDD\Entity\Identity;
use HMLB\DDD\Persistence\PersistentMessage;

/**
 * Interface PersistentMessageRepository.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
interface PersistentMessageRepository
{
    /**
     * @param Identity $identity
     *
     * @return PersistentMessage
     */
    public function get(Identity $identity);

    /**
     * @param string $class
     *
     * @return PersistentMessage[]
     */
    public function getByMessage(string $class);
}
