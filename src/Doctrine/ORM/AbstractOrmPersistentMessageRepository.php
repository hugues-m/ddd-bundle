<?php

namespace HMLB\DDDBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use HMLB\DDDBundle\Repository\PersistentMessageRepository;

/**
 * AbstractOrmPersistentMessageRepository.
 *
 * @author Hugues Maignol <hugues.maignol@kitpages.fr>
 */
abstract class AbstractOrmPersistentMessageRepository implements PersistentMessageRepository
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getByMessage(string $class)
    {
        $repo = $this->em->getRepository($class);

        return $repo->findAll();
    }
}
