<?php

namespace HMLB\DDDBundle\Persistence;

use HMLB\DDD\Message\Message;

/**
 * Interface PersistenceManager.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
interface MessagePersistenceManager
{
    public function persist(Message $message);

    public function save(Message $message);

    public function delete(Message $message);
}
