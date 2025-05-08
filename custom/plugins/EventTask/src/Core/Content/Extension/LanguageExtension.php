<?php declare(strict_types=1);

namespace EventTask\Core\Content\Extension;


use EventTask\Core\Content\Event\Aggregate\EventTranslationDefinition;
use EventTask\Core\Content\EventCategory\Aggregate\EventCategoryTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Language\LanguageDefinition;

class LanguageExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToManyAssociationField(
                'eventTransId',
                EventTranslationDefinition::class,
                'event_id',
            )
        );
        $collection->add(
            new OneToManyAssociationField(
                'eventCategoryTransId',
                EventCategoryTranslationDefinition::class,
                'event_category_id'
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return LanguageDefinition::class;
    }
}
