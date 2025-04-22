<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\BlogCategory\Aggregate;

use BlogDefinition\Core\Content\BlogCategory\BlogCategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Attribute\ForeignKey;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;

class BlogCategoryDefinitionTranslation extends EntityTranslationDefinition{
    public const ENTITY_NAME = 'blog_category_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getParentDefinitionClass(): string
    {
        return BlogCategoryDefinition::class;
    }
    protected function defineFields(): FieldCollection
    {
      return new FieldCollection([
          (new ForeignKey('blog_category_id','blogCategoryId',BlogCategoryDefinition::class,))->addFlags(new Required()),
          (new StringField('name','name'))->addFlags(new Required()),
      ]);
    }
}