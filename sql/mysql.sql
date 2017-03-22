CREATE TABLE `{token}` (
  `id`          INT(10) UNSIGNED           NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)               NOT NULL DEFAULT '',
  `token`       VARCHAR(255)
                CHARACTER SET utf8
                COLLATE utf8_bin                    DEFAULT NULL,
  `used`        INT(10) UNSIGNED           NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED           NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED        NOT NULL DEFAULT '1',
  `use_type`    ENUM ('general', 'module') NOT NULL DEFAULT 'general',
  `use_module`  VARCHAR(64)                NOT NULL DEFAULT '',
  `use_section` VARCHAR(64)                NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `status` (`status`),
  KEY `use_type` (`use_type`),
  KEY `use_module` (`use_module`),
  KEY `use_section` (`use_section`)
);