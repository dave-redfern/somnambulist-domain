<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Behaviours\QueryBuilder;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Trait HasJoinAlias
 *
 * Adapted from the DQL solution provided by:
 * @author zuzuleinen via Stackoverflow
 * @link https://stackoverflow.com/a/27020853
 *
 * @package    Somnambulist\Domain\Doctrine\Behaviours\QueryBuilder
 * @subpackage Somnambulist\Domain\Doctrine\Behaviours\QueryBuilder\HasJoinAlias
 */
trait HasJoinAlias
{

    /**
     * Returns true if the join alias is already defined in the query builder
     *
     * @param QueryBuilder $qb
     * @param string       $alias
     *
     * @return bool
     */
    public function hasJoinAlias(QueryBuilder $qb, string $alias): bool
    {
        $parts  = $qb->getQueryPart('join');
        $exists = false;

        foreach ($parts as $joins) {
            foreach ($joins as $join) {
                if ($join['joinAlias'] === $alias) {
                    $exists = true;
                    break 2;
                }
            }
        }

        return $exists;
    }
}
