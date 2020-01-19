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
        'token'  => [
            'title'  => _a('Token'),
            'access' => [//'admin',
            ],
        ],
        'user'   => [
            'title'  => _a('User'),
            'access' => [//'admin',
            ],
        ],
        'cron'   => [
            'title'  => _a('Cron'),
            'access' => [//'admin',
            ],
        ],
    ],
];