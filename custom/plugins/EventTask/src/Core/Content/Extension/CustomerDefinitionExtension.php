<?php declare(strict_types=1);

namespace EventTask\Core\Content\Extension;

use EventTask\Core\Content\Event\EventDefinition;
use EventTask\Core\Content\EventCustomerMapping\EventCustomerMappingDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomerDefinitionExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new ManyToManyAssociationField(
                'events',
                EventDefinition::class,
                EventCustomerMappingDefinition::class,
                'customer_id',
                'event_id'
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerDefinition::class;
    }
}
