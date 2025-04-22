CREATE TABLE `swag_paypal_vault_token` (
    `id` BINARY(16) NOT NULL,
    `customer_id` BINARY(16) NOT NULL,
    `payment_method_id` BINARY(16) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `token_customer` VARCHAR(255) NULL,
    `identifier` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.swag_paypal_vault_token.customer_id` (`customer_id`),
    KEY `fk.swag_paypal_vault_token.payment_method_id` (`payment_method_id`),
    CONSTRAINT `fk.swag_paypal_vault_token.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.swag_paypal_vault_token.payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_vault_token_mapping` (
    `customer_id` BINARY(16) NOT NULL,
    `payment_method_id` BINARY(16) NOT NULL,
    `token_id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`customer_id`,`payment_method_id`),
    KEY `fk.swag_paypal_vault_token_mapping.customer_id` (`customer_id`),
    KEY `fk.swag_paypal_vault_token_mapping.payment_method_id` (`payment_method_id`),
    CONSTRAINT `fk.swag_paypal_vault_token_mapping.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.swag_paypal_vault_token_mapping.payment_method_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_transaction_report` (
    `order_transaction_id` BINARY(16) NOT NULL,
    `order_transaction_version_id` BINARY(16) NOT NULL,
    `currency_iso` VARCHAR(255) NOT NULL,
    `total_price` DOUBLE NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`order_transaction_id`,`order_transaction_version_id`),
    KEY `fk.swag_paypal_transaction_report.order_transaction_id` (`order_transaction_id`,`order_transaction_version_id`),
    CONSTRAINT `fk.swag_paypal_transaction_report.order_transaction_id` FOREIGN KEY (`order_transaction_id`,`order_transaction_version_id`) REFERENCES `order_transaction` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_pos_sales_channel` (
    `id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NOT NULL,
    `product_stream_id` BINARY(16) NULL,
    `api_key` VARCHAR(8192) NOT NULL,
    `media_domain` VARCHAR(255) NULL,
    `webhook_signing_key` VARCHAR(64) NULL,
    `sync_prices` TINYINT(1) NULL DEFAULT '0',
    `replace` INT(11) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.swag_paypal_pos_sales_channel.product_stream_id` (`product_stream_id`),
    CONSTRAINT `fk.swag_paypal_pos_sales_channel.product_stream_id` FOREIGN KEY (`product_stream_id`) REFERENCES `product_stream` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_pos_sales_channel_inventory` (
    `sales_channel_id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `product_version_id` BINARY(16) NOT NULL,
    `stock` INT(11) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`sales_channel_id`,`product_id`,`product_version_id`),
    KEY `fk.swag_paypal_pos_sales_channel_inventory.product_id` (`product_id`,`product_version_id`),
    CONSTRAINT `fk.swag_paypal_pos_sales_channel_inventory.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_pos_sales_channel_run` (
    `id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NOT NULL,
    `task` VARCHAR(16) NOT NULL,
    `status` VARCHAR(255) NOT NULL,
    `message_count` INT(11) NOT NULL,
    `step_index` INT(11) NOT NULL,
    `steps` JSON NOT NULL,
    `finished_at` DATETIME(3) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `json.swag_paypal_pos_sales_channel_run.steps` CHECK (JSON_VALID(`steps`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_pos_sales_channel_run_log` (
    `id` BINARY(16) NOT NULL,
    `run_id` BINARY(16) NOT NULL,
    `level` INT(11) NOT NULL,
    `message` LONGTEXT NOT NULL,
    `product_id` BINARY(16) NULL,
    `product_version_id` BINARY(16) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.swag_paypal_pos_sales_channel_run_log.run_id` (`run_id`),
    CONSTRAINT `fk.swag_paypal_pos_sales_channel_run_log.run_id` FOREIGN KEY (`run_id`) REFERENCES `swag_paypal_pos_sales_channel_run` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_pos_sales_channel_media` (
    `sales_channel_id` BINARY(16) NOT NULL,
    `media_id` BINARY(16) NOT NULL,
    `lookup_key` VARCHAR(255) NULL,
    `url` VARCHAR(255) NULL,
    `created_at` DATETIME(3) NOT NULL,
    PRIMARY KEY (`sales_channel_id`,`media_id`),
    KEY `fk.swag_paypal_pos_sales_channel_media.media_id` (`media_id`),
    CONSTRAINT `fk.swag_paypal_pos_sales_channel_media.media_id` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `swag_paypal_pos_sales_channel_product` (
    `sales_channel_id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `product_version_id` BINARY(16) NOT NULL,
    `checksum` VARCHAR(32) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`sales_channel_id`,`product_id`,`product_version_id`),
    KEY `fk.swag_paypal_pos_sales_channel_product.product_id` (`product_id`,`product_version_id`),
    CONSTRAINT `fk.swag_paypal_pos_sales_channel_product.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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