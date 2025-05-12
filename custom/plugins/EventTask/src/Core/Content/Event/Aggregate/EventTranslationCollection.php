<?php declare(strict_types=1);

namespace EventTask\Core\Content\Event\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @package core
 * @method void                add(EventTranslationEntity $entity)
 * @method void                set(string $key, EventTranslationEntity $entity)
 * @method EventTranslationEntity[]    getIterator()
 * @method EventTranslationEntity[]    getElements()
 * @method EventTranslationEntity|null get(string $key)
 * @method EventTranslationEntity|null first()
 * @method EventTranslationEntity|null last()
 */
class EventTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return EventTranslationEntity::class;
    }
}