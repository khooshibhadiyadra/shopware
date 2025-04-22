<?php declare(strict_types=1);

use BlogDefinition\Core\Content\Blog\BlogDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Attribute\ForeignKey;
use Shopware\Core\Framework\DataAbstractionLayer\Attribute\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use \Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogProductDefinition extends MappingEntityDefinition{
//    public function getEntityName():string => 'blog_task';
    protected function defineFields(): FieldCollection
    {
    return new FieldCollection([
        (new ForeignKey('blog_id','blogId',BlogDefinition::class))->addFlags(new PrimaryKey()),
        (new ForeignKey('product_id','productId',ProductDefinition::class))->addFlags(new PrimaryKey())
    ]);
    }

}