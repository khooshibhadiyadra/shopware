<?php declare(strict_types=1);

namespace SwagAccount\Core\Content\Extension;

use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Country\CountryDefinition;
use SwagAccount\Core\Content\Example\SalesChannel\SwagAccountDefinition;


class CountryExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToManyAssociationField(
                'countryId',
                SwagAccountDefinition::class,
                'country_id',
            )
        );
    }
    public function getDefinitionClass(): string
    {
        return CountryDefinition::class;
    }
}