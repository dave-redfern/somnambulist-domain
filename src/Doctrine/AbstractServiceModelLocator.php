<?php declare(strict_types=1);

namespace Somnambulist\Components\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Somnambulist\Components\Collection\MutableCollection;
use Somnambulist\Components\Doctrine\Behaviours\EntityLocator\FindByUUID;
use Somnambulist\Components\Doctrine\Behaviours\EntityLocator\FindOrFail;
use Somnambulist\Components\Doctrine\Behaviours\EntityLocator\Paginate;

/**
 * Extends the Doctrine Bundle ServiceEntityRepository to a Locator while adding wrappers
 * to return ArrayCollection's and assorted helper methods for loading entities.
 *
 * To use in your class, inject the "registry" service and call the parent constructor.
 * For example:
 * <code>
 * class YourAggregateLocator extends AbstractServiceModelLocator
 * {
 *     public function __construct(ManagerRegistry $registry)
 *     {
 *         parent::__construct($registry, YourAggregate::class);
 *     }
 * }
 * </code>
 *
 * @template T of object
 * @template-extends EntityRepository<T>
 */
abstract class AbstractServiceModelLocator extends ServiceEntityRepository
{
    use FindOrFail;
    use FindByUUID;
    use Paginate;

    /**
     * Finds all entities in the repository.
     *
     * @return MutableCollection<object>
     */
    public function findAll(): MutableCollection
    {
        return $this->findBy([]);
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return MutableCollection<object>
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): MutableCollection
    {
        return new MutableCollection(parent::findBy($criteria, $orderBy, $limit, $offset));
    }
}
