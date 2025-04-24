<?php declare(strict_types=1);

namespace BlogTask\Core\Content\Blog\Aggregate;

use BlogTask\Core\Content\Blog\BlogDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
class BlogTranslationDefinition extends EntityTranslationDefinition
{
    const ENTITY_NAME = 'blog_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
//    public function getEntityClass(): string
//    {
//        return BlogTranslationEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return BlogTranslationCollection::class;
//    }
    protected function getParentDefinitionClass(): string
    {
        return BlogDefinition::class;
    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name','name'))->addFlags(new Required()),
            (new LongTextField('description','description'))->addFlags(new Required()),
        ]);
    }
}