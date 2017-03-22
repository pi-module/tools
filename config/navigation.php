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
    ),
);