<?php declare(strict_types=1);

namespace SwagAccount\Core\Content\Example\SalesChannel;

use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateDefinition;
use Shopware\Core\System\Country\CountryDefinition;
use SwagAccount\Core\Content\Example\SalesChannel\SwagAccount\Aggregate\SwagAccountTranslationDefinition;

class SwagAccountDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'swag_account';
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
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(),new Required()),
            new TranslatedField('name'),
            new TranslatedField('city'),
            new BoolField('active','active'),
            new FkField('country_id', 'countryId', CountryDefinition::class),
            new FkField('state_id','stateId',CountryStateDefinition::class),
            new FkField('media_id','mediaId',MediaDefinition::class,'id'),
            new FkField('product_id','productId',ProductDefinition::class),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),
            new ManyToOneAssociationField('country','country_id',CountryDefinition::class),
            new ManyToOneAssociationField('state','state_id',CountryStateDefinition::class),
            new OneToOneAssociationField('media','media_id', 'id',MediaDefinition::class,true),
            new ManyToOneAssociationField('product','product_id',ProductDefinition::class,'id',false),
            new TranslationsAssociationField(SwagAccountTranslationDefinition::class, 'swag_account_id'),
        ]);
    }
}