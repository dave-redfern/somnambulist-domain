<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Types\Web;

use Assert\Assert;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Somnambulist\Domain\Entities\Types\Web\Url;

/**
 * Class UrlType
 *
 * Store URL ValueObjects as strings and re-hydrate, without needing to use an
 * embeddable.
 *
 * @package    Somnambulist\Domain\Doctrine\Types
 * @subpackage Somnambulist\Domain\Doctrine\Types\Web\UrlType
 */
class UrlType extends Type
{

    public const NAME = 'url';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Url) {
            return $value;
        }

        try {
            $url = new Url($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, static::NAME);
        }

        return $url;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        try {
            if ($value instanceof Url || Assert::that($value)->url()) {
                return (string)$value;
            }
        } catch (InvalidArgumentException $e) {

        }

        throw ConversionException::conversionFailed($value, static::NAME);
    }

    public function getName()
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
