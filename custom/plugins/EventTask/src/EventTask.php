<?php declare(strict_types=1);

namespace EventTask;


use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EventTask extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }
        $connection = $this->container->get(Connection::class);
        try {
            $connection->executeStatement('DROP TABLE IF EXISTS `event_translation`');
            $connection->executeStatement('DROP TABLE IF EXISTS `event_category_translation`');
            $connection->executeStatement('DROP TABLE IF EXISTS `event_category_event`');
            $connection->executeStatement('DROP TABLE IF EXISTS `event_category`');
            $connection->executeStatement('DROP TABLE IF EXISTS `event`');
        } catch (Exception $e) {
        }

    }
}
