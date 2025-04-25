<?php declare(strict_types=1);

namespace BlogTask\Core\Content\Extension;

use BlogTask\Core\Content\Blog\BlogDefinition;
use BlogTask\Core\Content\BlogProductMapping\BlogProductMappingDefinition;
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
                'blogs',
                BlogDefinition::class,
                BlogProductMappingDefinition::class,
                'product_id',
                'blog_id'
            )
        );
    }
    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
