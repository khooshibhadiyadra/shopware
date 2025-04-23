<?php declare(strict_types=1);

use BlogDefinition\Core\Content\Blog\BlogDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;


class BlogProductDefinition extends MappingEntityDefinition{

    public const ENTITY_NAME = 'blog_task_blog_product';
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

//    public function getEntityClass(): string{
//        return BlogProductEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return BlogProductCollection::class;
//    }

    protected function defineFields(): FieldCollection
    {
    return new FieldCollection([
        //(new ForeignKey('blog_id','blogId',BlogDefinition::class))->addFlags(new PrimaryKey()),
        (new FkField('blog_id','blogId',BlogDefinition::class))->addFlags(new PrimaryKey()),
       // (new ForeignKey('product_id','productId',ProductDefinition::class))->addFlags(new PrimaryKey())
        (new FkField('product_id','productId',ProductDefinition::class))->addFlags(new PrimaryKey())
    ]);
    }

}