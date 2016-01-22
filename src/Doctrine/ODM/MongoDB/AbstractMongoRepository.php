<?php

namespace HMLB\DDDBundle\Doctrine\ODM\MongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use MongoId;
use HMLB\DDD\Entity\AggregateRoot;
use HMLB\DDD\Entity\Repository;
use HMLB\DDD\Entity\Identity;

/**
 * Aggregate root repository implementation for ORM.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
abstract class AbstractMongoRepository implements Repository
{
    /**
     * @var DocumentManager
     */
    protected $dm;
    /**
     * @var DocumentRepository
     */
    protected $documentRepository;

    public function __construct(DocumentManager $dm = null)
    {
        $this->dm = $dm;
        $this->documentRepository = $dm->getRepository($this->getClassName());
    }

    /**
     * @return AggregateRoot[]
     */
    public function getAll()
    {
        return $this->documentRepository->findAll();
    }

    /**
     * @param Identity $identity
     *
     * @return AggregateRoot
     */
    public function get(Identity $identity)
    {
        return $this->documentRepository->find($identity);
    }

    /**
     * @param AggregateRoot $document
     *
     * @return self
     */
    public function add(AggregateRoot $document)
    {
        $this->dm->persist($document);

        return $this;
    }

    /**
     * Finds Entities by a set of criteria.
     *
     * @param array    $criteria Query criteria
     * @param array    $sort     Sort array for Cursor::sort()
     * @param int|null $limit    Limit for Cursor::limit()
     * @param int|null $skip     Skip for Cursor::skip()
     *
     * @return AggregateRoot[]
     */
    protected function getBy(array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        return $this->documentRepository->findBy($criteria, $sort, $limit, $skip);
    }

    /**
     * Finds a single AggregateRoot by a set of criteria.
     *
     * @param array $criteria
     *
     * @return AggregateRoot
     */
    protected function getOneBy(array $criteria)
    {
        return $this->documentRepository->findOneBy($criteria);
    }

    /**
     * @param Identity $id
     *
     * @return MongoId
     */
    protected function createMongoIdFromIdentity(Identity $id)
    {
        return new MongoId((string) $id);
    }
}
