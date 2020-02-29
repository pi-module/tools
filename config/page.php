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
return [
    // Admin section
    'admin' => [
        [
            'label'      => _a('Token'),
            'controller' => 'token',
            'permission' => 'token',
        ],
        [
            'label'      => _a('Custom API'),
            'controller' => 'custom',
            'permission' => 'custom',
        ],
        [
            'label'      => _a('User'),
            'controller' => 'user',
            'permission' => 'user',
        ],
        [
            'label'      => _a('Cron'),
            'controller' => 'cron',
            'permission' => 'cron',
        ],
    ],
];