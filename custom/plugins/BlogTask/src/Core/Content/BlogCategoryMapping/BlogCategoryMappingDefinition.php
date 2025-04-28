<?php declare(strict_types=1);

namespace BlogTask\Core\Content\BlogCategoryMapping;

use BlogTask\Core\Content\Blog\BlogDefinition;
use BlogTask\Core\Content\BlogCategory\BlogCategoryCollection;
use BlogTask\Core\Content\BlogCategory\BlogCategoryDefinition;
use BlogTask\Core\Content\BlogCategory\BlogCategoryEntity;
use FooTask\Core\Content\Bar\BarDefinition;
use FooTask\Core\Content\Foo\FooDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogCategoryMappingDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog_category_blog';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string{
//        return BlogCategoryBlogEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return BlogCategoryBlogEntity::class;
//    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('blog_id', 'blogId', BlogDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('blog_category_id', 'blogCategoryId', BlogCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(BlogDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(BlogCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),

            new ManyToOneAssociationField('blog', 'blog_id', BlogDefinition::class, 'id'),
            new ManyToOneAssociationField('blog_category', 'blog_category_id', BlogCategoryDefinition::class, 'id')
        ]);
    }
}
