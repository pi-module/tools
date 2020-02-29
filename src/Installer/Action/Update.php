<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Tools\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Update as BasicUpdate;
use Pi\Application\Installer\SqlSchema;
use Zend\EventManager\Event;

class Update extends BasicUpdate
{
    /**
     * {@inheritDoc}
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('update.pre', [$this, 'updateSchema']);
        parent::attachDefaultListeners();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSchema(Event $e)
    {
        $moduleVersion = $e->getParam('version');

        // Update to version 0.4.0
        if (version_compare($moduleVersion, '0.4.0', '<')) {

            // Set category sql
            $category
                = <<<'EOD'
CREATE TABLE `{category}`
(
    `id`     INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `title`  VARCHAR(255)        NOT NULL                           DEFAULT '',
    `slug`   VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    `status` TINYINT(1) UNSIGNED NOT NULL                           DEFAULT '1',
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
);
EOD;

            // Set item sql
            $item
                = <<<'EOD'
CREATE TABLE `{item}`
(
    `id`       INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `title`    VARCHAR(255)     NOT NULL DEFAULT '',
    `key`      VARCHAR(255)     NOT NULL DEFAULT '',
    `value`    VARCHAR(255)     NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
);
EOD;

            // Add table of field_category
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($category);
                $sqlHandler->queryContent($item);
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'SQL schema query for author table failed: '
                            . $exception->getMessage(),
                    ]
                );

                return false;
            }
        }
    }
}