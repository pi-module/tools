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
return array(
    'front' => false,
    'admin' => array(
        'token' => array(
            'label'        => _a('Token'),
            'permission'   => array(
                'resource' => 'token',
            ),
            'route'        => 'admin',
            'module'       => 'tools',
            'controller'   => 'token',
            'action'       => 'index',
            'pages' => array(
                'token' => array(
                    'label'        => _a('Token'),
                    'permission'   => array(
                        'resource' => 'token',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'token',
                    'action'       => 'index',
                ),
                'update' => array(
                    'label'        => _a('Manage'),
                    'permission'   => array(
                        'resource' => 'token',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'token',
                    'action'       => 'update',
                ),
            ),
        ),

        'user' => array(
            'label'        => _a('User'),
            'permission'   => array(
                'resource' => 'user',
            ),
            'route'        => 'admin',
            'module'       => 'tools',
            'controller'   => 'user',
            'action'       => 'export',
            'pages' => array(
                'export' => array(
                    'label'        => _a('Export'),
                    'permission'   => array(
                        'resource' => 'user',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'user',
                    'action'       => 'export',
                ),
                'import' => array(
                    'label'        => _a('Import'),
                    'permission'   => array(
                        'resource' => 'user',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'user',
                    'action'       => 'import',
                ),
            ),
        ),

        'social' => array(
            'label'        => _a('Social'),
            'permission'   => array(
                'resource' => 'social',
            ),
            'route'        => 'admin',
            'module'       => 'tools',
            'controller'   => 'social',
            'action'       => 'index',
            'pages' => array(
                'social' => array(
                    'label'        => _a('Social'),
                    'permission'   => array(
                        'resource' => 'social',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'social',
                    'action'       => 'index',
                ),
                'update' => array(
                    'label'        => _a('Manage'),
                    'permission'   => array(
                        'resource' => 'social',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'social',
                    'action'       => 'update',
                ),
            ),
        ),

        'oauth' => array(
            'label'        => _a('oAuth'),
            'permission'   => array(
                'resource' => 'oauth',
            ),
            'route'        => 'admin',
            'module'       => 'tools',
            'controller'   => 'oauth',
            'action'       => 'index',
            'pages' => array(
                'oauth' => array(
                    'label'        => _a('Request'),
                    'permission'   => array(
                        'resource' => 'oauth',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'oauth',
                    'action'       => 'index',
                ),
                'user' => array(
                    'label'        => _a('User'),
                    'permission'   => array(
                        'resource' => 'oauth',
                    ),
                    'route'        => 'admin',
                    'module'       => 'tools',
                    'controller'   => 'oauth',
                    'action'       => 'user',
                ),
            ),
        ),

        'cron' => array(
            'label'        => _a('Cron'),
            'permission'   => array(
                'resource' => 'cron',
            ),
            'route'        => 'admin',
            'module'       => 'tools',
            'controller'   => 'cron',
            'action'       => 'index',
        ),
    ),
);