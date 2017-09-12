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
        'token' => array(
            'title' => _a('Token'),
            'access' => array(//'admin',
            ),
        ),
        'user' => array(
            'title' => _a('User'),
            'access' => array(//'admin',
            ),
        ),
        'social' => array(
            'title' => _a('Social'),
            'access' => array(//'admin',
            ),
        ),
        'oauth' => array(
            'title' => _a('oAuth'),
            'access' => array(//'admin',
            ),
        ),
        'cron' => array(
            'title' => _a('Cron'),
            'access' => array(//'admin',
            ),
        ),
    ),
);