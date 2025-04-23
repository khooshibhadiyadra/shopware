<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\BlogDefinition;

use BlogDefinition\BlogDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;


class BlogCategoryBlogDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog_category_blog';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

//    public function getEntityClass(): string
//    {
//        return BlogCategoryBlogEntity::class;
//    }
//
//    public function getCollectionClass(): string
//    {
//        return BlogCategoryBlogCollection::class;
//    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('blog_id','blogId',BlogDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(BlogDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('category_id','categoryId',BlogCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(BlogCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),

        ]);
    }
}