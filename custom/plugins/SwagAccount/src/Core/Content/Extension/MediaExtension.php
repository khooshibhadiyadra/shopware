<?php declare(strict_types=1);

namespace SwagAccount\Core\Content\Extension;

use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use SwagAccount\Core\Content\Example\SalesChannel\SwagAccountDefinition;

class MediaExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField(
                'swag_account',
                'id',
                'media_id',
                SwagAccountDefinition::class,
                false
            )
        );
    }
    public function getDefinitionClass(): string
    {
        return MediaDefinition::class;
    }
}




