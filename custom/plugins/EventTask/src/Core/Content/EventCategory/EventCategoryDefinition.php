<?php declare(strict_types=1);

namespace EventTask\Core\Content\EventCategory;

use EventTask\Core\Content\Event\EventDefinition;
use EventTask\Core\Content\EventCategory\Aggregate\EventCategoryTranslationDefinition;
use EventTask\Core\Content\EventCategoryMapping\EventCategoryMappingDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class EventCategoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'event_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string{
//        return EventCategoryEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return EventCategoryCollection::class;
//    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new TranslatedField('name'),

            new ManyToManyAssociationField(
                'events',
                EventDefinition::class,
                EventCategoryMappingDefinition::class,
                'event_category_id',
                'event_id'
            ),
            (new TranslationsAssociationField(EventCategoryTranslationDefinition::class, 'event_category_id'))->addFlags(new Required()),
        ]);
    }
}


