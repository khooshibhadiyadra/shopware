<?php declare(strict_types=1);

namespace EventTask\Core\Content\Extension;

use EventTask\Core\Content\Event\EventDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomerExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
                        new OneToManyAssociationField(
                            'customerId',
                            EventDefinition::class,
                            'customer_id',
                        )
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerDefinition::class;
    }
}
