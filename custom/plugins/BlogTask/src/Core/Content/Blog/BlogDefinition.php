<?php declare(strict_types=1);

namespace BlogTask\Core\Content\Blog;

use BlogTask\Core\Content\Blog\Aggregate\BlogTranslationDefinition;
use BlogTask\Core\Content\BlogCategory\BlogCategoryDefinition;
use BlogTask\Core\Content\BlogCategoryMapping\BlogCategoryMappingDefinition;
use BlogTask\Core\Content\BlogProductMapping\BlogProductMappingDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;


class BlogDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string
//    {
//        return BlogEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return BlogCollection::class;
//    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id','id'))->addFlags(new PrimaryKey(),new Required()),
            new TranslatedField('name'),
            new TranslatedField('description'),
            (new DateField('release_date','releaseDate'))->addFlags(new Required()),
            (new BoolField('active','active'))->addFlags(new Required()),
            (new StringField('categories','categories'))->addFlags(new Required()),
            (new StringField('author','author'))->addFlags(new Required()),
            new TranslationsAssociationField(BlogTranslationDefinition::class, 'blog_id'),

            new ManyToManyAssociationField(
                'blogCategories',
                BlogCategoryDefinition::class,
                BlogCategoryMappingDefinition::class,
                'blog_id',
                'blog_category_id'
            ),

            new ManyToManyAssociationField(
                'products',
                ProductDefinition::class,
                BlogProductMappingDefinition::class,
                'blog_id',
                'product_id'
            ),
            new OneToManyAssociationField(
                'blogCategoryMappings',
                BlogCategoryMappingDefinition::class,
                'blog_id'
            ),

            new OneToManyAssociationField(
                'blogProductMappings',
                BlogProductMappingDefinition::class,
                'blog_id'
            ),

        ]);
    }
}