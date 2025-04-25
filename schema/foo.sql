CREATE TABLE `foo` (
    `id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `foo_bar` (
    `bar_id` BINARY(16) NOT NULL,
    `foo_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`bar_id`,`foo_id`),
    KEY `fk.foo_bar.bar_id` (`bar_id`),
    KEY `fk.foo_bar.foo_id` (`foo_id`),
    CONSTRAINT `fk.foo_bar.bar_id` FOREIGN KEY (`bar_id`) REFERENCES `bar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk.foo_bar.foo_id` FOREIGN KEY (`foo_id`) REFERENCES `foo` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;