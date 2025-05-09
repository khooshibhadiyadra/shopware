<?php declare(strict_types=1);

namespace EventTask\Core\Content\Event;

use EventTask\Core\Content\Event\Aggregate\EventTranslationDefinition;
use EventTask\Core\Content\EventCategoryMapping\EventCategoryMappingDefinition;
use EventTask\Core\Content\EventCustomerMapping\EventCustomerMappingDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Content\Category\CategoryDefinition;

class EventDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'event';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return EventCollection::class;
    }

    public function getEntityClass(): string
    {
        return EventEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new TranslatedField('name'),
            new TranslatedField('description'),
            (new BoolField('is_active', 'isActive'))->addFlags(new Required()),
            (new DateField('event_date', 'eventDate'))->addFlags(new Required()),

//            new FkField('organized_by_id', 'organizedById', CustomerDefinition::class),
//            new ManyToOneAssociationField('organizedBy', 'organized_by_id', CustomerDefinition::class, 'id', false),

//            new FkField('event_category_id', 'eventCategoryId', CategoryDefinition::class),
//            new ManyToOneAssociationField('eventCategory', 'event_category_id', CategoryDefinition::class, 'id', false),

            new ManyToManyAssociationField(
                'organizedBys',
                CustomerDefinition::class,
                EventCustomerMappingDefinition::class,
                'event_id',
                'customer_id'
            ),
            new ManyToManyAssociationField(
                'eventCategories',
                CategoryDefinition::class,
                EventCategoryMappingDefinition::class,
                'event_id',
                'event_category_id'
            ),
            (new TranslationsAssociationField(EventTranslationDefinition::class, 'event_id'))->addFlags(new Required()),
        ]);
    }
}
