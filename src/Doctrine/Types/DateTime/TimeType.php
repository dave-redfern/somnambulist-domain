<?php declare(strict_types=1);

namespace Somnambulist\Domain\Doctrine\Types\DateTime;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Somnambulist\Domain\Entities\Types\DateTime\DateTime;

/**
 * Type that maps an SQL TIME to a DateTime object.
 *
 * Based on: Doctrine\DBAL\Types\TimeType
 */
class TimeType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Types::TIME_MUTABLE;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format($platform->getTimeFormatString()) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }

        $val = DateTime::createFromFormat('!' . $platform->getTimeFormatString(), $value);
        if (!$val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getTimeFormatString());
        }

        return $val;
    }
}
