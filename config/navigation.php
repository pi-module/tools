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

        'social' => [
            'label'      => _a('Social'),
            'permission' => [
                'resource' => 'social',
            ],
            'route'      => 'admin',
            'module'     => 'tools',
            'controller' => 'social',
            'action'     => 'index',
            'pages'      => [
                'social' => [
                    'label'      => _a('Social'),
                    'permission' => [
                        'resource' => 'social',
                    ],
                    'route'      => 'admin',
                    'module'     => 'tools',
                    'controller' => 'social',
                    'action'     => 'index',
                ],
                'update' => [
                    'label'      => _a('Manage'),
                    'permission' => [
                        'resource' => 'social',
                    ],
                    'route'      => 'admin',
                    'module'     => 'tools',
                    'controller' => 'social',
                    'action'     => 'update',
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