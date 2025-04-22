<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\Blog;

use BlogDefinition\Core\Content\BlogCategory\BlogCategoryDefinition;
use BlogProductDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
class BlogDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog_task';
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string{
//        return SwagAccountEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return SwagAccountCollection::class;
//    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(),new Required()),
            new TranslatedField('name'),
            new TranslatedField('description'),
            new DateField('created_at','createdAt'),
            new BoolField('active','active'),
            new FkField('categories', 'categories', CategoryDefinition::class),
            new TranslatedField('author'),
            new ManyToManyAssociationField('categories',BlogCategoryDefinition::class,BlogBlogCategoryDefinition::class,'blog_id','blog_category_id'),
            new ManyToManyAssociationField('products',ProductDefinition::class,ProductDefinition::class,BlogProductDefinition::class,'product_id'),
        ]);
    }
}