<?php declare(strict_types=1);

namespace BlogTask\Core\Content\BlogCategory\Aggregate;

use BlogTask\Core\Content\BlogCategory\BlogCategoryCollection;
use BlogTask\Core\Content\BlogCategory\BlogCategoryDefinition;
use BlogTask\Core\Content\BlogCategory\BlogCategoryEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Language\LanguageDefinition;

class BlogCategoryTranslationDefinition extends EntityTranslationDefinition
{
    const ENTITY_NAME = 'blog_category_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string{
//        return BlogCategoryTranslationEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return BlogCategoryTranslationCollection::class;
//    }
    protected function getParentDefinitionClass(): string
    {
        return BlogCategoryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new FkField('blog_category_id', 'blogCategoryId', BlogCategoryDefinition::class))->addFlags(new Required()),
            (new FkField('language_id', 'languageId', LanguageDefinition::class))->addFlags(new Required()),
        ]);
    }
}
