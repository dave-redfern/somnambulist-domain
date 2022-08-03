<?php declare(strict_types=1);

namespace Somnambulist\Components\Queries\Behaviours;

use Somnambulist\Components\Collection\FrozenCollection;
use function class_alias;

trait CanSortQuery
{
    private readonly FrozenCollection $orderBy;

    public function orderBy(): FrozenCollection
    {
        return $this->orderBy;
    }
}

class_alias(CanSortQuery::class, 'Somnambulist\Components\Queries\Behaviours\CanOrderQuery');
