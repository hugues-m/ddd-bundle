<?php

namespace HMLB\DDDBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use HMLB\DDD\Entity\AggregateRoot;
use HMLB\DDD\Entity\Identity;
use HMLB\DDD\Entity\Repository;

/**
 * Aggregate root repository implementation for ORM.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
abstract class AbstractORMRepository implements Repository
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var EntityRepository
     */
    protected $entityRepository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->entityRepository = $em->getRepository($this->getClassName());
    }

    /**
     * @return AggregateRoot[]
     */
    public function getAll(): array
    {
        return $this->entityRepository->findAll();
    }

    /**
     * @param Identity $identity
     *
     * @return AggregateRoot
     */
    public function get(Identity $identity)
    {
        return $this->entityRepository->find($identity);
    }

    /**
     * @param AggregateRoot $document
     */
    public function add(AggregateRoot $document)
    {
        $this->em->persist($document);
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
    protected function getBy(array $criteria, array $sort = null, int $limit = null, int $skip = null)
    {
        return $this->entityRepository->findBy($criteria, $sort, $limit, $skip);
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
        return $this->entityRepository->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [], array $rootProperties = [])
    {
        if (null === $criteria) {
            return;
        }

        foreach ($criteria as $property => $value) {
            if (null === $value) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->isNull($this->getPropertyName($property, $rootProperties)));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->in($this->getPropertyName($property, $rootProperties), $value)
                );
            } elseif ('' !== $value) {
                $sanitizedProperty = $this->sanitizeParameterName($property);
                $queryBuilder
                    ->andWhere(
                        $queryBuilder->expr()->eq(
                            $this->getPropertyName($property, $rootProperties),
                            ':'.$sanitizedProperty
                        )
                    )
                    ->setParameter($sanitizedProperty, $value);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = null, array $rootProperties = [])
    {
        if (null === $sorting) {
            return;
        }
        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property, $rootProperties), $order);
            }
        }
    }

    /**
     * @param string $name
     * @param array  $rootProperties Properties that will not be bound to the object alias
     *
     * @return string
     */
    protected function getPropertyName(string $name, array $rootProperties = []): string
    {
        if (!in_array($name, $rootProperties) && false === mb_strpos($name, '.')) {
            return $this->getAlias().'.'.$name;
        }

        return $name;
    }

    /**
     * If properties have complex path, it cannot be used as parameter key in
     * requests.
     * This sanitize property path for proper requests.
     *
     * @param string $property
     *
     * @return string
     */
    protected function sanitizeParameterName(string $property): string
    {
        return str_replace('.', '_', $property);
    }

    /**
     * @return string The main alias used for this repository's entity in queries.
     */
    protected function getAlias(): string
    {
        $className = $this->getClassName();
        $classPieces = explode('\\', $className);
        $last = array_pop($classPieces);

        return strtolower($last);
    }
}
