<?php declare(strict_types=1);

namespace EventTask\Core\Content\Extension;

use EventTask\Core\Content\Event\EventDefinition;
use EventTask\Core\Content\EventCategoryMapping\EventCategoryMappingDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CategoryDefinitionExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new ManyToManyAssociationField(
                'events',
                EventDefinition::class,
                EventCategoryMappingDefinition::class,
                'category_id',
                'event_id'
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return CategoryDefinition::class;
    }
}
