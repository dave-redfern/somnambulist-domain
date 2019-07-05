<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Enumerations\Constructors;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Eloquent\Enumeration\AbstractEnumeration;
use InvalidArgumentException;

/**
 * Class GenericEloquentEnumeration
 *
 * @package    Somnambulist\Domain\Doctrine\Enumerations
 * @subpackage Somnambulist\Domain\Doctrine\Enumerations\GenericEloquentEnumeration
 */
class GenericEloquentEnumeration
{

    /**
     * @param string           $value
     * @param string           $class
     * @param AbstractPlatform $platform
     *
     * @return AbstractEnumeration
     * @throws InvalidArgumentException
     */
    public function __invoke(string $value, string $class, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        /** @var AbstractEnumeration $class */
        if (null !== $member = $class::memberOrNullByValue($value)) {
            return $member;
        }

        throw new InvalidArgumentException(sprintf(
            '"%s" is not a valid value for "%s"; should be one of: "%s"',
            $value,
            $class,
            implode(', ', $class::members())
        ));
    }
}
