<?php //declare(strict_types=1);
//
//namespace SwagTask\Core\Content\Example\SalesChannel;
//
//use Shopware\Core\Content\Media\MediaDefinition;
//use Shopware\Core\Content\Product\ProductDefinition;
//use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
//use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
//use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
//use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateDefinition;
//use Shopware\Core\System\Country\CountryDefinition;
//use SwagAccount\Core\Content\Example\SalesChannel\SwagAccount\Aggregate\SwagAccountTranslationDefinition;
//
//class SwagTaskDefinition extends EntityDefinition{
//
//    public const ENTITY_NAME='swag_task';
//    public function getEntityName(): string{
//        return self::ENTITY_NAME;
//    }
////    public function getCollectionClass(): string{
////
////    }
//
//protected function defineFields(): FieldCollection
//{
//    return new FieldCollection([
//        (new IdField('id', 'id'))->addFlags(new PrimaryKey(),new Required()),
//        new StringField('name','name'),
//        new StringField('description','description'),
//        new DateField('release_date','releaseDate'),
//        new BoolField('active','active'),
//        new StringField('author','author'),
//        new ManyToManyAssociationField(
//            'categories',
//            BlogCategoryDefinition::class,
//            BlogProductDefinition::class,
//            'blog_id',
//            'category_id'
//        ),
//        new ManyToManyAssociationField(
//            'products',
//            ProductDefinition::class,
//            BlogProductDefinition::class,
//            'blog_id',
//            'product_id'
//        )
//    ]);
//}
//}