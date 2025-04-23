<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\BlogDefinition\Extension;

use BlogDefinition\Core\Content\BlogDefinition\Aggregate\BlogTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Language\LanguageDefinition;

class  LanguageExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToManyAssociationField(
                'BlogTranslation',
                BlogTranslationDefinition::class,
                'blog_id'
            )
        );
    }
    public function getDefinitionClass(): string
    {
        return LanguageDefinition::class;
    }
}