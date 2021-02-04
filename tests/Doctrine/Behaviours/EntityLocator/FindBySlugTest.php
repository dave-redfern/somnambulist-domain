<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Tests\Doctrine\Behaviours\EntityLocator;

use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Domain\Doctrine\Behaviours\EntityLocator\FindBySlug;
use Somnambulist\Components\Domain\Entities\Exceptions\EntityNotFoundException;
use stdClass;

/**
 * Class FindBySlugTest
 *
 * @package    Somnambulist\Components\Domain\Tests\Doctrine\Behaviours\EntityLocator
 * @subpackage Somnambulist\Components\Domain\Tests\Doctrine\Behaviours\EntityLocator\FindBySlugTest
 *
 * @group doctrine
 * @group doctrine-behaviours
 * @group doctrine-behaviours-locator
 */
class FindBySlugTest extends TestCase
{

    public function testFindBySlugCallsFindOneBy()
    {
        $mock = $this->getMockForTrait(FindBySlug::class);
        $mock
            ->expects($this->once())
            ->method('findOneBy')
        ;

        /** @var FindBySlug $mock */
        $mock->findBySlug('bob');
    }

    public function testFindOrFailBySlugCallsFindOneBy()
    {
        $mock = $this->getMockForTrait(FindBySlug::class);
        $mock
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(new stdClass())
        ;

        /** @var FindBySlug $mock */
        $mock->findOrFailBySlug('bob');
    }

    public function testFindOrFailBySlugCallsRaisesExceptionIfNotFound()
    {
        $mock = $this->getMockForTrait(FindBySlug::class, [], '', true, true, true, ['findBySlug']);
        $mock
            ->expects($this->once())
            ->method('findBySlug')
            ->willReturn(null)
        ;
        $mock
            ->expects($this->once())
            ->method('getEntityName')
            ->willReturn('class')
        ;

        $this->expectException(EntityNotFoundException::class);
        /** @var FindBySlug $mock */
        $mock->findOrFailBySlug('bob');
    }
}
