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
    'front' => false,
    'admin' => [
        'token' => [
            'label'      => _a('Token'),
            'permission' => [
                'resource' => 'token',
            ],
            'route'      => 'admin',
            'module'     => 'tools',
            'controller' => 'token',
            'action'     => 'index',
            'pages'      => [
                'token'  => [
                    'label'      => _a('Token'),
                    'permission' => [
                        'resource' => 'token',
                    ],
                    'route'      => 'admin',
                    'module'     => 'tools',
                    'controller' => 'token',
                    'action'     => 'index',
                ],
                'update' => [
                    'label'      => _a('Manage'),
                    'permission' => [
                        'resource' => 'token',
                    ],
                    'route'      => 'admin',
                    'module'     => 'tools',
                    'controller' => 'token',
                    'action'     => 'update',
                ],
            ],
        ],

        'user' => [
            'label'      => _a('User'),
            'permission' => [
                'resource' => 'user',
            ],
            'route'      => 'admin',
            'module'     => 'tools',
            'controller' => 'user',
            'action'     => 'export',
            'pages'      => [
                'export' => [
                    'label'      => _a('Export'),
                    'permission' => [
                        'resource' => 'user',
                    ],
                    'route'      => 'admin',
                    'module'     => 'tools',
                    'controller' => 'user',
                    'action'     => 'export',
                ],
                'import' => [
                    'label'      => _a('Import'),
                    'permission' => [
                        'resource' => 'user',
                    ],
                    'route'      => 'admin',
                    'module'     => 'tools',
                    'controller' => 'user',
                    'action'     => 'import',
                ],
            ],
        ],

        'cron' => [
            'label'      => _a('Cron'),
            'permission' => [
                'resource' => 'cron',
            ],
            'route'      => 'admin',
            'module'     => 'tools',
            'controller' => 'cron',
            'action'     => 'index',
        ],
    ],
];