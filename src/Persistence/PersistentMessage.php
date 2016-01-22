<?php

namespace HMLB\DDDBundle\Persistence;

use HMLB\DDD\Entity\Identity;

/**
 * A message that will be persisted.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
interface PersistentMessage
{
    /**
     * Returns the message's identity.
     *
     * @return Identity
     */
    public function getId();

    /**
     * Create an identity for the message.
     *
     * @return self
     */
    public function initializeId();
}
