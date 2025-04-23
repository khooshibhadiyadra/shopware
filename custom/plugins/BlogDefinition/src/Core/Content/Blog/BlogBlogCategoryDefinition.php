<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\Blog;

use BlogDefinition\Core\Content\BlogCategory\BlogCategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\{FkField, Flag\PrimaryKey, Flag\Required, IdField};
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;


class BlogBlogCategoryDefinition extends MappingEntityDefinition
{
public const ENTITY_NAME = 'blog_task_blog_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//public function getEntityClass(): string => BlogBlogCategoryEntity::class;
//public function getCollectionClass(): string => BlogBlogCategoryCollection::class;
protected function defineFields(): FieldCollection
{
return new FieldCollection([
(new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
(new FkField('blog_id', 'blogId', BlogDefinition::class))->addFlags(new Required()),
(new FkField('blog_category_id', 'blogCategoryId', BlogCategoryDefinition::class))->addFlags(new Required()),
]);
}
}