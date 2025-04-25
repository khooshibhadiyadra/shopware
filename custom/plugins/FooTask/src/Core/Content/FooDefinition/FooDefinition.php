<?php declare(strict_types=1);

namespace FooTask\Core\Content\FooDefinition;

    use FooTask\Core\Content\BarDefintion\BarDefinition;
    use FooTask\Core\Content\FooBarMapping\FooBarMappingDefinition;
    use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
    use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
    use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
    use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
    use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
    use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

    class FooDefinition extends EntityDefinition
    {
        public const ENTITY_NAME = 'foo_definition';

        public function getEntityName(): string
        {
            return self::ENTITY_NAME;
        }

        protected function defineFields(): FieldCollection
        {
            return new FieldCollection([
                (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),

                new ManyToManyAssociationField(
                    'bars',
                    BarDefinition::class,
                    FooBarMappingDefinition::class,
                    'foo_id',
                    'bar_id'
                ),
            ]);
        }
    }