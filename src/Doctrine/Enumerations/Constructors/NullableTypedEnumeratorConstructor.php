<?php declare(strict_types=1);

namespace Somnambulist\Components\Doctrine\Enumerations\Constructors;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Eloquent\Enumeration\AbstractEnumeration;
use InvalidArgumentException;

class NullableTypedEnumeratorConstructor
{
    public function __construct(private readonly string $class)
    {
    }

    /**
     * @param mixed           $value
     * @param AbstractPlatform $platform
     *
     * @return AbstractEnumeration|null
     * @throws InvalidArgumentException
     */
    public function __invoke(mixed $value, AbstractPlatform $platform): ?AbstractEnumeration
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
