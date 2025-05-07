<?php declare(strict_types=1);

namespace BlogTask\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1746595275blog extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1746595275;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
        CREATE TABLE IF NOT EXISTS `blog` (
    `id` BINARY(16) NOT NULL,
    `release_date` DATE NOT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blog_category` (
    `id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blog_category_blog` (
    `blog_category_id` BINARY(16) NOT NULL,
    `blog_id` BINARY(16) NOT NULL,
    `blog_category_version_id` BINARY(16) NOT NULL,
    `blog_version_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`blog_category_id`,`blog_id`,`blog_category_version_id`,`blog_version_id`),
    KEY `fk.blog_category_blog.blog_id` (`blog_id`),
    KEY `fk.blog_category_blog.blog_category_id` (`blog_category_id`),
    CONSTRAINT `fk.blog_category_blog.blog_id` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.blog_category_blog.blog_category_id` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blog_product` (
    `blog_id` BINARY(16) NOT NULL,
    `blog_version_id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `product_version_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`blog_id`,`blog_version_id`,`product_id`,`product_version_id`),
    KEY `fk.blog_product.blog_id` (`blog_id`),
    KEY `fk.blog_product.product_id` (`product_id`,`product_version_id`),
    CONSTRAINT `fk.blog_product.blog_id` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.blog_product.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blog_translation` (
    `name` VARCHAR(255) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `author` VARCHAR(255) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `blog_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`blog_id`,`language_id`),
    KEY `fk.blog_translation.blog_id` (`blog_id`),
    KEY `fk.blog_translation.language_id` (`language_id`),
    CONSTRAINT `fk.blog_translation.blog_id` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.blog_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blog_category_translation` (
    `name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `blog_category_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`blog_category_id`,`language_id`),
    KEY `fk.blog_category_translation.blog_category_id` (`blog_category_id`),
    KEY `fk.blog_category_translation.language_id` (`language_id`),
    CONSTRAINT `fk.blog_category_translation.blog_category_id` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.blog_category_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }
}
