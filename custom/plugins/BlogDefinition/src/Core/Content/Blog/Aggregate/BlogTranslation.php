<?php declare(strict_types=1);

namespace BlogDefinition\Core\Content\Blog\Aggregate;
use BlogDefinition\Core\Content\Blog\BlogDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Attribute\ForeignKey;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogTranslation extends EntityTranslationDefinition{
    public const ENTITY_NAME = 'blog_task_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getParentDefinitionClass(): string
    {
        return BlogTranslation::class;
    }
    protected function defineFields(): FieldCollection{
        return new FieldCollection([
            (new ForeignKey('blog_id','blogId',BlogDefinition::class))->addFlags(new Required()),
            (new StringField('name','name'))->addFlags(new Required()),
            new LongTextField('description','description')
        ]);
    }
}