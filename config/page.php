<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
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
            'label'      => _a('User'),
            'controller' => 'user',
            'permission' => 'user',
        ],
        [
            'label'      => _a('Social'),
            'controller' => 'social',
            'permission' => 'social',
        ],
        [
            'label'      => _a('Cron'),
            'controller' => 'cron',
            'permission' => 'cron',
        ],
    ],
];