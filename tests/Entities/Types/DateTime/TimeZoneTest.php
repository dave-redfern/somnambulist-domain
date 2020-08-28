<?php declare(strict_types=1);

namespace Somnambulist\Domain\Tests\Entities\Types\DateTime;

use PHPUnit\Framework\TestCase;
use Somnambulist\Domain\Entities\Types\DateTime\TimeZone;

/**
 * Class TimeZoneTest
 *
 * @package    Somnambulist\Domain\Tests\Entities\Types\DateTime
 * @subpackage Somnambulist\Domain\Tests\Entities\Types\DateTime\TimeZoneTest
 *
 * @group      entities
 * @group      entities-types
 * @group      entities-types-datetime
 */
class TimeZoneTest extends TestCase
{


    public function testCreate()
    {
        $vo = new TimeZone('America/Toronto');

        $this->assertEquals('America/Toronto', $vo->toString());
    }

    public function testCreateFromFactory()
    {
        $vo = TimeZone::create('America/Toronto');

        $this->assertEquals('America/Toronto', $vo->toString());
    }

    public function testCreateFromFactoryUsesSystemDefault()
    {
        $vo = TimeZone::create();

        $this->assertEquals(date_default_timezone_get(), $vo->toString());
    }

    public function testCanCastToString()
    {
        $vo = new TimeZone('America/Toronto');

        $this->assertEquals('America/Toronto', (string)$vo);
    }

    public function testCanGetNative()
    {
        $vo = new TimeZone('America/Toronto');

        $this->assertInstanceOf(\DateTimeZone::class, $vo->toNative());
    }

    public function testCanCompareOtherObjects()
    {
        $vo1 = new TimeZone('America/Toronto');
        $vo2 = new TimeZone('America/New_York');

        $this->assertTrue($vo1->equals($vo1));
        $this->assertFalse($vo1->equals($vo2));
    }

    public function testCantSetArbitraryProperties()
    {
        $vo      = new TimeZone('America/Toronto');
        $vo->foo = 'bar';

        $this->assertObjectNotHasAttribute('foo', $vo);
    }
}
