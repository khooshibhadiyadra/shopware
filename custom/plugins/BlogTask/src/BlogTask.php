<?php declare(strict_types=1);

namespace BlogTask;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class BlogTask extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }
        $connection = $this->container->get(Connection::class);
        try {
            $connection->executeStatement('DROP TABLE IF EXISTS `blog_translation`');
            $connection->executeStatement('DROP TABLE IF EXISTS `blog_product`');
            $connection->executeStatement('DROP TABLE IF EXISTS `blog_category_translation`');
            $connection->executeStatement('DROP TABLE IF EXISTS `blog_category_blog`');
            $connection->executeStatement('DROP TABLE IF EXISTS `blog_category`');
            $connection->executeStatement('DROP TABLE IF EXISTS `blog`');
        } catch (Exception $e) {
        }
    }
}
