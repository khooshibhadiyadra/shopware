<?php declare(strict_types=1);

namespace EventTask\Core\Content\EventCategory\Aggregate;
use EventTask\Core\Content\EventCategory\EventCategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class EventCategoryTranslationDefinition extends EntityTranslationDefinition
{
    const ENTITY_NAME = 'event_category_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string{
//        return EventCategoryTranslationEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return EventCategoryTranslationCollection::class;
//    }
    protected function getParentDefinitionClass(): string
    {
        return EventCategoryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
        ]);
    }
}
