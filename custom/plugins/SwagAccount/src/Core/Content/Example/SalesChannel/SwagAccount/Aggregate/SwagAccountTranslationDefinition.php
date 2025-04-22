<?php declare(strict_types=1);

namespace SwagAccount\Core\Content\Example\SalesChannel\SwagAccount\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Language\LanguageDefinition;
use SwagAccount\Core\Content\Example\SalesChannel\SwagAccountDefinition;


class SwagAccountTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'swag_account_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getParentDefinitionClass(): string
    {
        return SwagAccountDefinition::class;
    }
//    public function getEntityClass(): string
//    {
//        return SwagAccountTranslationEntity::class;
//    }
//    public function getCollectionClass(): string
//    {
//        return SwagAccountTranslationCollection::class;
//    }
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name','name'))->addFlags(new Required()),
            (new StringField('city','city'))->addFlags(new Required()),
            (new FkField('swag_account_id','swagAccountId',SwagAccountDefinition::class))->addFlags(new Required()),
            (new FkField('language_id','languageId',LanguageDefinition::class))->addFlags(new Required()),
        ]);
    }
}