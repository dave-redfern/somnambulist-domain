<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Entities\Types\Measure;

use Somnambulist\Components\Domain\Entities\AbstractValueObject;
use function sprintf;

/**
 * Class Area
 *
 * @package    Somnambulist\Components\Domain\Entities\Types\Measure
 * @subpackage Somnambulist\Components\Domain\Entities\Types\Measure\Area
 */
final class Area extends AbstractValueObject
{
    public function __construct(private float $value, private AreaUnit $unit)
    {
    }

    public function toString(): string
    {
        return sprintf('%s %s', $this->value, $this->unit);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function unit(): AreaUnit
    {
        return $this->unit;
    }
}
