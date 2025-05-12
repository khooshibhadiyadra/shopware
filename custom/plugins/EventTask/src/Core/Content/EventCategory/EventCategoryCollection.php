<?php declare(strict_types=1);

namespace EventTask\Core\Content\EventCategory;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @package core
 * @method void                add(EventCategoryEntity $entity)
 * @method void                set(string $key, EventCategoryEntity $entity)
 * @method EventCategoryEntity[]    getIterator()
 * @method EventCategoryEntity[]    getElements()
 * @method EventCategoryEntity|null get(string $key)
 * @method EventCategoryEntity|null first()
 * @method EventCategoryEntity|null last()
 */
class EventCategoryCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return EventCategoryEntity::class;
    }
}