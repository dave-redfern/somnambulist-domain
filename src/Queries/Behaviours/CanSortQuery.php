<?php declare(strict_types=1);

namespace Somnambulist\Domain\Queries\Behaviours;

use Somnambulist\Collection\FrozenCollection;

/**
 * Trait CanSortQuery
 *
 * @package Somnambulist\Domain\Queries\Behaviours
 * @subpackage Somnambulist\Domain\Queries\Behaviours\CanSortQuery
 */
trait CanSortQuery
{

    /**
     * An array of field -> direction pairs
     *
     * @var FrozenCollection
     */
    private $orderBy;

    public function getOrderBy(): FrozenCollection
    {
        return $this->orderBy;
    }
}
