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
        ),
    ),
);