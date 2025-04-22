<?php declare(strict_types=1);

namespace SwagBlog\Core\Content\Example\SalesChannel;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;


class SwagBlogCategoryDefinition extends EntityDefinition{

    public const ENTITY_NAME='swag_blog_category';
    public function getEntityName(): string{
        return self::ENTITY_NAME;
    }

//    public function getCollectionClass(): string{
//
//    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            new IdField('id', 'id'),
            new FkField('blog_id','blogId',\SwagBlog\Core\Content\Example\SalesChannel\SwagBlogDefinition::class),
            new FkField('product_id','productId',ProductDefinition::class)
        ]);
    }
}