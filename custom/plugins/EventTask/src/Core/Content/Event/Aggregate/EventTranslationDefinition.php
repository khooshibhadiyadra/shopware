<?php declare(strict_types=1);

namespace EventTask\Core\Content\Event\Aggregate;


use EventTask\Core\Content\Event\EventDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class EventTranslationDefinition extends EntityTranslationDefinition
{
    const ENTITY_NAME = 'event_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getEntityClass(): string{
        return EventTranslationEntity::class;
    }
    public function getCollectionClass(): string
    {
        return EventTranslationCollection::class;
    }
    protected function getParentDefinitionClass(): string
    {
        return EventDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new LongTextField('description', 'description'))->addFlags(new Required()),
        ]);
    }
}
