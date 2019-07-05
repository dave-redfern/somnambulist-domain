<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Enumerations\Serializers;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;
use Somnambulist\Domain\Entities\Types\Geography\Country;

/**
 * Class CountrySerializer
 *
 * @package    Somnambulist\Domain\Doctrine\Enumerations\Serializers
 * @subpackage Somnambulist\Domain\Doctrine\Enumerations\Serializers\CountrySerializer
 */
class CountrySerializer
{

    /**
     * @param object           $value
     * @param string           $class
     * @param AbstractPlatform $platform
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function __invoke($value, string $class, AbstractPlatform $platform)
    {
        if (!$value instanceof Country) {
            throw new InvalidArgumentException(sprintf(
                '"%s" can only be used with "%s"', __CLASS__, Country::class
            ));
        }

        return (string)$value->code();
    }
}
