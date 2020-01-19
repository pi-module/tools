CREATE TABLE `{token}`
(
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `uid`         INT(10) UNSIGNED    NOT NULL                           DEFAULT '0',
    `title`       VARCHAR(255)        NOT NULL                           DEFAULT '',
    `token`       VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    `used`        INT(10) UNSIGNED    NOT NULL                           DEFAULT '0',
    `time_create` INT(10) UNSIGNED    NOT NULL                           DEFAULT '0',
    `time_used`   INT(10) UNSIGNED    NOT NULL                           DEFAULT '0',
    `time_expire` INT(10) UNSIGNED    NOT NULL                           DEFAULT '0',
    `status`      TINYINT(1) UNSIGNED NOT NULL                           DEFAULT '1',
    PRIMARY KEY (`id`),
    UNIQUE KEY `token` (`token`),
    KEY `status` (`status`)
);