<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Tests\Support\Stubs\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Somnambulist\Components\Domain\Entities\AggregateRoot;
use Somnambulist\Components\Domain\Entities\Types\Identity\Uuid;
use Somnambulist\Components\Domain\Tests\Support\Stubs\Events\MyEntityAddedAnotherEntity;
use Somnambulist\Components\Domain\Tests\Support\Stubs\Events\MyEntityCreatedEvent;
use Somnambulist\Components\Domain\Tests\Support\Stubs\Events\MyEntityNameUpdatedEvent;
use Somnambulist\Components\Domain\Tests\Support\Stubs\Events\MyEntityWasRemovedEvent;

class MyEntity extends AggregateRoot
{

    protected string     $name;
    protected string     $another;
    protected Collection $related;

    public function __construct(Uuid $id, string $name, string $another)
    {
        $this->id      = $id;
        $this->name    = $name;
        $this->another = $another;
        $this->related = new ArrayCollection();

        $this->initializeTimestamps();

        $this->raise(MyEntityCreatedEvent::class, ['id' => $id, 'name' => $name, 'another' => $another]);
    }

    public function updateName($name)
    {
        $this->name = $name;

        $this->raise(MyEntityNameUpdatedEvent::class, ['id' => $this->id, 'new' => $name, 'previous' => $this->name]);
    }

    public function remove()
    {
        $this->raise(MyEntityWasRemovedEvent::class);
    }

    public function addRelated($name, $another, $createdAt)
    {
        $this->related->add(new MyOtherEntity($this, $name, $another, $createdAt));

        $this->raise(MyEntityAddedAnotherEntity::class, [
            'id'    => $this->id,
            'name'  => $this->name,
            'other' => [
                'name'       => $name,
                'another'    => $another,
                'created_at' => $createdAt,
            ],
        ]);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAnother()
    {
        return $this->another;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getRelated(): Collection
    {
        return $this->related;
    }
}
