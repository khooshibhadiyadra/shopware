CREATE TABLE `swag_account` (
    `id` BINARY(16) NOT NULL,
    `active` TINYINT(1) NULL DEFAULT '0',
    `country_id` BINARY(16) NULL,
    `state_id` BINARY(16) NULL,
    `media_id` BINARY(16) NULL,
    `product_id` BINARY(16) NULL,
    `product_version_id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `json.swag_account.translated` CHECK (JSON_VALID(`translated`)),
    KEY `fk.swag_account.country_id` (`country_id`),
    KEY `fk.swag_account.state_id` (`state_id`),
    KEY `fk.swag_account.product_id` (`product_id`,`product_version_id`),
    CONSTRAINT `fk.swag_account.country_id` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.swag_account.state_id` FOREIGN KEY (`state_id`) REFERENCES `country_state` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.swag_account.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_account_translation` (
    `name` VARCHAR(255) NOT NULL,
    `city` VARCHAR(255) NOT NULL,
    `swag_account_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`swag_account_id`,`language_id`),
    KEY `fk.swag_account_translation.swag_account_id` (`swag_account_id`),
    KEY `fk.swag_account_translation.language_id` (`language_id`),
    CONSTRAINT `fk.swag_account_translation.swag_account_id` FOREIGN KEY (`swag_account_id`) REFERENCES `swag_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.swag_account_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;