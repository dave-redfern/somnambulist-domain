<?php declare(strict_types=1);

namespace Somnambulist\Domain\Utils;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Ramsey\Uuid\Uuid as UuidFactory;
use Ramsey\Uuid\UuidInterface;
use Somnambulist\Domain\Entities\Types\Identity\AbstractIdentity;
use Somnambulist\Domain\Entities\Types\Identity\Uuid;
use function implode;
use function is_a;
use function sprintf;

/**
 * Class IdentityGenerator
 *
 * @package Somnambulist\Domain\Utils
 * @subpackage Somnambulist\Domain\Utils\IdentityGenerator
 */
final class IdentityGenerator
{

    public static function random(): Uuid
    {
        return static::randomOfType(Uuid::class);
    }

    public static function randomOfType(string $type = Uuid::class): AbstractIdentity
    {
        return static::make($type, UuidFactory::uuid4());
    }

    /**
     * Creates a UUID v5 in the provided namespace using the values
     *
     * Multiple string values can be passed and they will be dot separated as the identity
     * string. E.g.: `IdentityGenerator::hashed($ns, 'var', 'foo', 'bar')` would hash the
     * string "var.foo.bar" (without quotes).
     *
     * This will produce the same UUID provided the arguments and namespace passed in are
     * the same. It's useful to generate a UUID from e.g.: a domain name / URL resource that
     * should always hash to the same thing.
     *
     * @param Uuid   $namespace
     * @param string ...$values
     *
     * @return Uuid
     */
    public static function hashed(Uuid $namespace, ...$values): Uuid
    {
        return static::hashedOfType($namespace, Uuid::class, ...$values);
    }

    public static function hashedOfType(Uuid $namespace, string $type = Uuid::class, ...$values): AbstractIdentity
    {
        return static::make($type, UuidFactory::uuid5((string)$namespace, implode('.', $values)));
    }

    private static function make(string $type, UuidInterface $id): AbstractIdentity
    {
        if (!is_a($type, AbstractIdentity::class, $string = true)) {
            throw new InvalidArgumentException(
                sprintf('Identity type "%s" does not extend "%s"', $type, AbstractIdentity::class),
                Assertion::INVALID_CLASS,
                'type'
            );
        }

        return new $type($id->toString());
    }
}
