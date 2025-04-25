<?php declare(strict_types=1);

namespace FooTask\Core\Content\Bar;

use FooTask\Core\Content\Foo\FooDefinition;
use FooTask\Core\Content\FooBar\FooBarMappingDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BarDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'bar';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),

            new ManyToManyAssociationField(
                'foos',
                FooDefinition::class,
                FooBarMappingDefinition::class,
                'bar_id',
                'foo_id'
            ),
        ]);
    }
}