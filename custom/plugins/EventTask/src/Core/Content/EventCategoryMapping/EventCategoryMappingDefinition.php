<?php declare(strict_types=1);

namespace EventTask\Core\Content\EventCategoryMapping;


use EventTask\Core\Content\Event\EventDefinition;
use EventTask\Core\Content\EventCategory\EventCategoryDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class EventCategoryMappingDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'event_category_event';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    protected function defineFields(): FieldCollection
    {

        return new FieldCollection([
            (new FkField('event_category_id', 'eventCategoryId', EventCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('event_id', 'eventId', EventDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(EventCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(EventDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('event', 'event_id', EventDefinition::class, 'id'),
            new ManyToOneAssociationField('eventCategory', 'event_category_id', EventCategoryDefinition::class, 'id'),
        ]);

    }
}
