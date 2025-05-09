<?php declare(strict_types=1);

namespace EventTask\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1746784893eventprac extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1746784893;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
        CREATE TABLE IF NOT EXISTS `event` (
    `id` BINARY(16) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `event_date` DATE NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_category` (
    `id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_category_event` (
    `event_category_id` BINARY(16) NOT NULL,
    `event_id` BINARY(16) NOT NULL,
    `event_category_version_id` BINARY(16) NOT NULL,
    `event_version_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`event_category_id`,`event_id`,`event_category_version_id`,`event_version_id`),
    KEY `fk.event_category_event.event_id` (`event_id`),
    KEY `fk.event_category_event.event_category_id` (`event_category_id`),
    CONSTRAINT `fk.event_category_event.event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.event_category_event.event_category_id` FOREIGN KEY (`event_category_id`) REFERENCES `event_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_customer` (
    `event_id` BINARY(16) NOT NULL,
    `event_version_id` BINARY(16) NOT NULL,
    `customer_id` BINARY(16) NOT NULL,
    `customer_version_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`event_id`,`event_version_id`,`customer_id`,`customer_version_id`),
    KEY `fk.event_customer.event_id` (`event_id`),
    KEY `fk.event_customer.customer_id` (`customer_id`),
    CONSTRAINT `fk.event_customer.event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.event_customer.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_translation` (
    `name` VARCHAR(255) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `event_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`event_id`,`language_id`),
    KEY `fk.event_translation.event_id` (`event_id`),
    KEY `fk.event_translation.language_id` (`language_id`),
    CONSTRAINT `fk.event_translation.event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.event_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_category_translation` (
    `name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `event_category_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`event_category_id`,`language_id`),
    KEY `fk.event_category_translation.event_category_id` (`event_category_id`),
    KEY `fk.event_category_translation.language_id` (`language_id`),
    CONSTRAINT `fk.event_category_translation.event_category_id` FOREIGN KEY (`event_category_id`) REFERENCES `event_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.event_category_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }
}
