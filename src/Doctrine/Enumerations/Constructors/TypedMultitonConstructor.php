<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Enumerations\Constructors;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;
use Somnambulist\Domain\Entities\AbstractMultiton;

/**
 * Class TypedMultitonConstructor
 *
 * @package    Somnambulist\Domain\Doctrine\Enumerations\Constructors
 * @subpackage Somnambulist\Domain\Doctrine\Enumerations\Constructors\TypedMultitonConstructor
 */
class TypedMultitonConstructor
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
     * @return AbstractMultiton
     * @throws InvalidArgumentException
     */
    public function __invoke(string $value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        /** @var AbstractMultiton $class */
        if (null !== $member = $this->class::memberOrNullByKey($value)) {
            return $member;
        }

        throw new InvalidArgumentException(sprintf(
            '"%s" is not a valid key for "%s"; should be one of: "%s"',
            $value,
            $this->class,
            implode(', ', $this->class::keys())
        ));
    }
}
