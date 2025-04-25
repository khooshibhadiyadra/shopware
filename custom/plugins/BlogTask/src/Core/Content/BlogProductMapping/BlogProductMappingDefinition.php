<?php declare(strict_types=1);

namespace BlogTask\Core\Content\BlogProductMapping;

use BlogTask\Core\Content\Blog\BlogDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogProductMappingDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog_product';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

//    public function getEntityClass(): string
//    {
//        return BlogProductMappingEntity::class;
//    }
//
//    public function getCollectionClass(): string
//    {
//        return BlogProductMappingCollection::class;
//    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('blog_id','blogId',BlogDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(BlogDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('product_id','productId',ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id'),
            new ManyToOneAssociationField('blog','blog_id',BlogDefinition::class,'id'),
        ]);
    }
}