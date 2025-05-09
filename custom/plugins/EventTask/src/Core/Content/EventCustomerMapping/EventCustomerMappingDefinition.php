<?php declare(strict_types=1);

namespace EventTask\Core\Content\EventCustomerMapping;

use EventTask\Core\Content\Event\EventDefinition;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class EventCustomerMappingDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'event_customer';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('event_id', 'eventId', EventDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(EventDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(CustomerDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('event', 'event_id', EventDefinition::class, 'id'),
            new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class, 'id')
        ]);
    }
}
