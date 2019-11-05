# Symfony Messenger Integration

Symfony Messenger can be used to implement:

 * CommandBus
 * QueryBus
 * EventBus

These implementations are based on the Symfony documentation:

 * https://symfony.com/doc/current/messenger/handler_results.html
 * https://symfony.com/doc/current/messenger/multiple_buses.html

This requires setting up messenger as follows:

```yaml
framework:
    messenger:
        failure_transport: failed
        default_bus: command.bus

        buses:
            # creates a MessageBusInterface instance available on the $commandBus argument
            command.bus:
                middleware:
                    - validation
                    - doctrine_transaction

            query.bus:
                middleware:
                    - validation

            event.bus:
                middleware:
                    - validation

        transports:
            # https://symfony.com/doc/current/messenger.html#transport-configuration
            domain_events:
                dsn: '%env(MESSENGER_TRANSPORT_DSN)%/domain_events'
                options:
                    exchange:
                        name: domain_events
                        type: fanout
                        durable: true
            # optional to capture failures
            failed: 'doctrine://default?queue_name=failed'
            # synchronous transport
            sync: 'sync://'

        routing:
            # Route your messages to the transports
            Somnambulist\Domain\Events\AbstractDomainEvent: domain_events
            Somnambulist\Domain\Commands\AbstractCommand: sync
            Somnambulist\Domain\Queries\AbstractQuery: sync
```

The above configuration will automatically route all extended Commands and Queries to the sync
transport and the DomainEvent instances to the event bus named `domain_events`.

Then the following services should be defined in `services.yaml`:

```yaml
services:
    Somnambulist\Domain\Events\Messenger\EventBus:
    
    Somnambulist\Domain\Events\EventBus:
        alias: Somnambulist\Domain\Events\Messenger\EventBus
        public: true
    
    Somnambulist\Domain\Commands\Messenger\CommandBus:
    
    Somnambulist\Domain\Commands\CommandBus:
        alias: Somnambulist\Domain\Commands\Messenger\CommandBus
        public: true
    
    Somnambulist\Domain\Queries\Messenger\QueryBus:
    
    Somnambulist\Domain\Queries\QueryBus:
        alias: Somnambulist\Domain\Queries\Messenger\QueryBus
        public: true
```

To use the underlying Messenger instances, type-hint a `MessageBusInterface` and then use
the appropriate camelCased variable name:

```php
<?php
use Symfony\Component\Messenger\MessageBusInterface;

class MyController extends Controller
{
    public function __construct(MessageBusInterface $commandBus)
    {
        // the command bus Messenger instance will be injected
        $this->commandBus = $commandBus;
    }
}
```

Now the services can be type-hinted using the interfaces and auto-wired correctly.

The `EventBus` can be injected into the Doctrine subscriber to allow for the domain events
to be automatically broadcast postFlush.

### Broadcast Domain Events

The Doctrine event subscriber supports broadcasting domain events if the EventBus is configured.
To enable the event subscriber add the following to your `services.yaml`:

```yaml
services:
    Somnambulist\Domain\Events\Publishers\Doctrine\DomainEventPublisher:
        tags: ['doctrine.event_subscriber']
```

This will register a Doctrine event subscriber that listens to:

 * prePersist
 * preFlush
 * postFlush
 
Events are queued, sorted by the timestamp to ensure the correct order and sent postFlush.
Events are still broadcast through Doctrine Event Manager.

__Note:__ by default Messenger 4.3+ defaults to PHP native serializer. This will mean that the
message payload contains PHP serialized objects. To send JSON payloads, a custom serializer is
needed. This must be configured as follows:

Install Symfony Serializer if not already installed: `composer req symfony/serializer symfony/property-access`.

__Note:__ `property-access` is required to enable the `ObjectNormalizer` that is used to
serialize the envelope stamp objects.

Add the following service definition and optional alias if desired:
```yaml
services:
    Somnambulist\Domain\Events\Messenger\Serializer:
        
    somnambulist.domain.event_serializer:
        alias: Somnambulist\Domain\Events\Messenger\Serializer
```

Set the serializer on the domain_events transport:
```yaml
framework:
    messenger:
        transports:
            # https://symfony.com/doc/current/messenger.html#transport-configuration
            domain_events:
                dsn: '%env(MESSENGER_TRANSPORT_DSN)%/domain_events'
                serializer: somnambulist.domain.event_serializer
```

You will need to require the Symfony serializer component for this to work. See: 
https://symfony.com/doc/current/messenger.html#serializing-messages for further
documentation.

To use the Symfony Serializer by default for all serialization (except domain events):

```yaml
framework:
    messenger:
        serializer:
            default_serializer: messenger.transport.symfony_serializer
            symfony_serializer:
                format: json
                context: { }
```

__Note:__ the `EventBus` provided here is specifically for domain events. For general events
consider adding a separate bus.
