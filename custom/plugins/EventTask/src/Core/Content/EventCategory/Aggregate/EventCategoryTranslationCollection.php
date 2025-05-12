<?php declare(strict_types=1);

namespace EventTask\Core\Content\EventCategory\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @package core
 * @method void                add(EventCategoryTranslationEntity $entity)
 * @method void                set(string $key, EventCategoryTranslationEntity $entity)
 * @method EventCategoryTranslationEntity[]    getIterator()
 * @method EventCategoryTranslationEntity[]    getElements()
 * @method EventCategoryTranslationEntity|null get(string $key)
 * @method EventCategoryTranslationEntity|null first()
 * @method EventCategoryTranslationEntity|null last()
 */
class EventCategoryTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return EventCategoryTranslationEntity::class;
    }
}