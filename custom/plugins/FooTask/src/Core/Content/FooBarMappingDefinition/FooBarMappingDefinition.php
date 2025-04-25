<?php declare(strict_types=1);

namespace FooTask\Core\Content\FooBarMappingDefinition;

use FooTask\Core\Content\BarDefintion\BarDefinition;
use FooTask\Core\Content\FooDefinition\FooDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;


class FooBarMappingDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'foo_bar';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('bar_id', 'barId', BarDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('foo_id', 'fooId', FooDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('bar', 'bar_id', BarDefinition::class, 'id'),
            new ManyToOneAssociationField('foo', 'foo_id', FooDefinition::class, 'id')
        ]);
    }
}