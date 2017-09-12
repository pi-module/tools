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
    // Admin section
    'admin' => array(
        array(
            'label'      => _a('Token'),
            'controller' => 'token',
            'permission' => 'token',
        ),
        array(
            'label'      => _a('User'),
            'controller' => 'user',
            'permission' => 'user',
        ),
        array(
            'label'      => _a('Social'),
            'controller' => 'social',
            'permission' => 'social',
        ),
        array(
            'label'      => _a('oAuth'),
            'controller' => 'oauth',
            'permission' => 'oauth',
        ),
        array(
            'label'      => _a('Cron'),
            'controller' => 'cron',
            'permission' => 'cron',
        ),
    ),
);