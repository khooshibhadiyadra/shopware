<?php declare(strict_types=1);

namespace EventTask\Core\Content\Event;

use EventTask\Core\Content\Event\Aggregate\EventTranslationDefinition;
use EventTask\Core\Content\EventCategory\EventCategoryDefinition;
use EventTask\Core\Content\EventCategoryMapping\EventCategoryMappingDefinition;
use EventTask\Core\Content\EventProductMapping\EventProductMappingDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
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

class EventDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'event';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string{
//        return EventEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return EventCollection::class;
//    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new TranslatedField('name'),
            new TranslatedField('description'),
            (new DateField('release_date', 'releaseDate'))->addFlags(new Required()),
            (new BoolField('active', 'active'))->addFlags(new Required()),
            new ManyToManyAssociationField(
                'products',
                ProductDefinition::class,
                EventProductMappingDefinition::class,
                'event_id',
                'product_id'
            ),
            new ManyToManyAssociationField(
                'eventCategories',
                EventCategoryDefinition::class,
                EventCategoryMappingDefinition::class,
                'event_id',
                'event_category_id'
            ),
            (new TranslationsAssociationField(EventTranslationDefinition::class, 'event_id'))->addFlags(new Required()),
        ]);
    }
}
