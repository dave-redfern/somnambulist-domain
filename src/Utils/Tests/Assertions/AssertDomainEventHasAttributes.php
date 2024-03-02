<?php declare(strict_types=1);

namespace Somnambulist\Components\Utils\Tests\Assertions;

use Somnambulist\Components\Collection\MutableCollection;
use Somnambulist\Components\Events\AbstractEvent;
use Somnambulist\Components\Models\AggregateRoot;

/**
 * Test Helper
 *
 * Test a given Aggregate Root (or entity implementing RaisesDomainEvents) that it has
 * an event of type AND that the event has specific attributes and values.
 */
trait AssertDomainEventHasAttributes
{
    /**
     * @param AggregateRoot $entity     The entity that raises events
     * @param string        $event      The event class name to check for
     * @param array         $attributes An array of key -> value pairs to check in the first matched event
     */
    public function assertDomainEventHasAttributes(AggregateRoot $entity, string $event, array $attributes): void
    {
        $events = new MutableCollection($entity->releaseAndResetEvents());

        /** @var AbstractEvent $matched */
        $matched = $events->filter(fn(AbstractEvent $evt) => $evt instanceof $event)->first();

        $this->assertInstanceOf($event, $matched);

        foreach ($attributes as $key => $value) {
            $this->assertTrue($matched->payload()->has($key), sprintf('Event "%s" was expected to have property "%s"', $event, $key));
            $this->assertEquals(
                $value, $matched->payload()->get($key),
                sprintf('Event "%s" property "%s" was expected to be "%s", but "%s" received', $event, $key, $value, $matched->payload()->get($key))
            );
        }
    }
}
