<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Enumerations\Constructors;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Eloquent\Enumeration\AbstractEnumeration;
use InvalidArgumentException;

/**
 * Class NullableTypedEnumeratorConstructor
 *
 * @package    Somnambulist\Domain\Doctrine\Enumerations\Constructors
 * @subpackage Somnambulist\Domain\Doctrine\Enumerations\Constructors\NullableTypedEnumeratorConstructor
 */
class NullableTypedEnumeratorConstructor
{

    private string $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return AbstractEnumeration
     * @throws InvalidArgumentException
     */
    public function __invoke(string $value, AbstractPlatform $platform)
    {
        if (null !== $member = $this->class::memberOrNullByValue($value)) {
            return $member;
        }

        throw new InvalidArgumentException(sprintf(
            '"%s" is not a valid value for "%s"; should be one of: "%s"',
            $value,
            $this->class,
            implode(', ', $this->class::members())
        ));
    }
}
