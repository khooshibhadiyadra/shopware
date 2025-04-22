<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\BlogCategory;

use BlogDefinition\Core\Content\Blog\BlogDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
class BlogCategoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog_category';
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
            (new IdField('id','id'))->addFlags(new Required()),
            new TranslatedField('name'),
            new ManyToManyAssociationField('blogs',BlogDefinition::class,BlogBlogCategoryDefinition::class,'blog_category_id','blog_id'),
        ]);
    }
}