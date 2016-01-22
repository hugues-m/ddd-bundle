<?php

namespace HMLB\DDDBundle\Persistence;

use HMLB\DDD\Entity\Identity;

/**
 * Trait PersistentMessageCapability.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
trait PersistentMessageCapabilities
{
    private $id;

    /**
     * Returns the message's identity.
     *
     * @return Identity
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Create an identity for the message.
     *
     * @return self
     */
    public function initializeId()
    {
        $this->id = new Identity();

        return $this;
    }
}
