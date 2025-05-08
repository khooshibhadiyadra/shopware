<?php declare(strict_types=1);

namespace EventTask\Core\Content\Extension;

use EventTask\Core\Content\Event\EventDefinition;
use EventTask\Core\Content\EventProductMapping\EventProductMappingDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ProductExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new ManyToManyAssociationField(
                'events',
                EventDefinition::class,
                EventProductMappingDefinition::class,
                'product_id',
                'event_id'
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
