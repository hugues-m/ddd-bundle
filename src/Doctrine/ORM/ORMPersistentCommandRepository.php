<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Doctrine\ORM;

use HMLB\DDD\Entity\Identity;
use HMLB\DDD\Message\Command\PersistentCommand;
use HMLB\DDDBundle\Repository\PersistentCommandRepository;

/**
 * ORMCommandRepository.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class ORMPersistentCommandRepository extends AbstractOrmPersistentMessageRepository implements PersistentCommandRepository
{
    /**
     * @param Identity $identity
     *
     * @return PersistentCommand
     */
    public function get(Identity $identity)
    {
        return $this->em->getRepository(PersistentCommand::class)->find($identity);
    }
}
