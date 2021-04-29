<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Utils;

use InvalidArgumentException;
use ReflectionObject;
use function is_iterable;
use function is_object;
use function is_scalar;
use function sprintf;

/**
 * Class ObjectDiff
 *
 * @package    Somnambulist\Components\Domain\Utils
 * @subpackage Somnambulist\Components\Domain\Utils\ObjectDiff
 * @experimental added in 4.2.0
 */
class ObjectDiff
{

    /**
     * @param object $a
     * @param object $b
     *
     * @return array
     */
    public function diff(object $a, object $b): array
    {
        if ($a::class !== $b::class) {
            throw new InvalidArgumentException(sprintf('Expected instance of "%s" to diff with, received "%s"', $a::class, $b::class));
        }
        if ($a === $b) {
            return [];
        }

        $diff   = [];
        $refObj = new ReflectionObject($a);

        do {
            foreach ($refObj->getProperties() as $prop) {
                if ($prop->isStatic()) {
                    // ignore static properties as these are usually instances or caches
                    continue;
                }

                $prop->setAccessible(true);
                $mine   = $prop->getValue($a);
                $theirs = $prop->getValue($b);

                $this->testScalarValue($diff, $prop->getName(), $mine, $theirs);
                $this->testIterableValue($diff, $prop->getName(), $mine, $theirs);
                $this->testObjectValue($diff, $prop->getName(), $mine, $theirs);
            }
        } while ($refObj = $refObj->getParentClass());

        return array_filter($diff);
    }

    private function diffIterable($mine, $theirs): array
    {
        $diff = [];

        foreach ($mine as $key => $value) {
            $this->testScalarValue($diff, (string)$key, $value, $theirs[$key] ?? null);
            $this->testIterableValue($diff, (string)$key, $value, $theirs[$key] ?? null);
            $this->testObjectValue($diff, (string)$key, $value, $theirs[$key] ?? null);
        }

        return $diff;
    }

    private function testScalarValue(array &$diff, string $prop, mixed $mine, mixed $theirs): void
    {
        if ((is_scalar($mine) || is_null($mine))  && $mine !== $theirs) {
            $diff[$prop] = [
                'mine'   => $mine,
                'theirs' => $theirs,
            ];
        }
    }

    private function testIterableValue(array &$diff, string $prop, mixed $mine, mixed $theirs): void
    {
        if (is_iterable($mine) && $mine != $theirs) {
            $diff[$prop] = $this->diffIterable($mine, $theirs);
        }
    }

    private function testObjectValue(array &$diff, string $prop, mixed $mine, mixed $theirs): void
    {
        if (is_object($mine) && is_object($theirs) && $mine !== $theirs) {
            $diff[$prop] = array_filter($this->diff($mine, $theirs));
        }
    }
}
