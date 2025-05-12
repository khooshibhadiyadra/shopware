<?php declare(strict_types=1);

namespace EventTask\Core\Content\Event;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @package core
 * @method void                add(EventEntity $entity)
 * @method void                set(string $key, EventEntity $entity)
 * @method EventEntity[]    getIterator()
 * @method EventEntity[]    getElements()
 * @method EventEntity|null get(string $key)
 * @method EventEntity|null first()
 * @method EventEntity|null last()
 */
class EventCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return EventEntity::class;
    }
}