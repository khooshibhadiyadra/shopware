<?php
declare(strict_types=1);

namespace BlogTask\Core\Content\BlogCategory;

use BlogTask\Core\Content\Blog\BlogDefinition;
use BlogTask\Core\Content\BlogCategory\Aggregate\BlogCategoryTranslationDefinition;
use BlogTask\Core\Content\BlogCategoryMapping\BlogCategoryMappingDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogCategoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'blog_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new TranslatedField('name'),
            new TranslationsAssociationField(BlogCategoryTranslationDefinition::class, 'blog_category_id'),

            // Correct reverse side of many-to-many with blogs
            new ManyToManyAssociationField(
                'blogs',
                BlogDefinition::class,
                BlogCategoryMappingDefinition::class,
                'blog_category_id',
                'blog_id'
            ),
        ]);
    }
}
