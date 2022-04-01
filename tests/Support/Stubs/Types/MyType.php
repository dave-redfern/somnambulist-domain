<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Tests\Support\Stubs\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Somnambulist\Components\Domain\Doctrine\Types\EnumerationBridge;

class MyType extends EnumerationBridge
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'FOO BAR';
    }
}
