CREATE TABLE `{token}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)        NOT NULL DEFAULT '',
  `token`       VARCHAR(255)
                CHARACTER SET utf8
                COLLATE utf8_bin             DEFAULT NULL,
  `used`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `use_module`  VARCHAR(64)         NOT NULL DEFAULT '',
  `use_section` VARCHAR(64)         NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `status` (`status`),
  KEY `use_module` (`use_module`),
  KEY `use_section` (`use_section`)
);

CREATE TABLE `{social}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)        NOT NULL DEFAULT '',
  `slug`        VARCHAR(255)        NOT NULL DEFAULT '',
  `url`         VARCHAR(255)        NOT NULL DEFAULT '',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `icon`        VARCHAR(32)         NOT NULL DEFAULT '',
  `order`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `url` (`url`),
  KEY `status` (`status`),
  KEY `order` (`order`)
);