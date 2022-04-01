<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Doctrine\Enumerations\Constructors;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Eloquent\Enumeration\AbstractEnumeration;
use InvalidArgumentException;
use function in_array;

/**
 * Class TypedEnumerableConstructor
 *
 * @package    Somnambulist\Components\Domain\Doctrine\Enumerations\Constructors
 * @subpackage Somnambulist\Components\Domain\Doctrine\Enumerations\Constructors\TypedEnumerableConstructor
 */
class TypedEnumerableConstructor
{
    public function __construct(private string $class, private string $preCastAs = 'string')
    {
        if (!in_array($preCastAs, ['string', 'int'])) {
            throw new InvalidArgumentException(sprintf('preCastAs must be one of string or int, "%s" is not supported', $preCastAs));
        }
    }

    /**
     * @param string|int       $value
     * @param AbstractPlatform $platform
     *
     * @return AbstractEnumeration|null
     * @throws InvalidArgumentException
     */
    public function __invoke(mixed $value, AbstractPlatform $platform): ?AbstractEnumeration
    {
        if (is_null($value)) {
            return null;
        }
        if ('int' === $this->preCastAs) {
            $value = (int)$value;
        }

        $class = $this->class;

        if (null !== $member = $class::memberOrNullByValue($value)) {
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
