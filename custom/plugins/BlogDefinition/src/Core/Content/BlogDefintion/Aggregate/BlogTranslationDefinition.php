<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\BlogDefinition\Aggregate;

use BlogDefinition\BlogDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogTranslationDefinition extends EntityTranslationDefinition{

    public const ENTITY_NAME = 'blog_translation';

    public function getEntityName(): string{
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

public function defineFields(): FieldCollection
{
    return new FieldCollection([
            (new StringField('name','name'))->addFlags(new Required()),
            (new LongTextField('description','description'))->addFlags(new Required()),
            (new FkField('blog_id','blogId',BlogDefinition::class))->addFlags(new Required()),
            (new FkField('language_id','languageId',BlogTranslationDefinition::class))->addFlags(new Required())
    ]
    );
}
}


