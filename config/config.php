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
    'category' => array(
        array(
            'title' => _a('Admin'),
            'name' => 'admin'
        ),
        array(
            'name'      => 'cron',
            'title'     => _t('Cron'),
        ),
        array(
            'name'      => 'oauth',
            'title'     => _t('oAuth'),
        ),
    ),
    'item' => array(
        // Admin
        'admin_perpage' => array(
            'category' => 'admin',
            'title' => _a('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 25
        ),

        // Cron
        'cron_active' => array(
            'category'      => 'cron',
            'title'         => _a('Active cron'),
            'description'   => '',
            'edit'          => 'checkbox',
            'filter'        => 'number_int',
            'value'         => 0
        ),

        'cron_token' => array(
            'category'      => 'cron',
            'title'         => _a('Cron token'),
            'edit'          => 'text',
            'filter'        => 'string',
            'value'         => md5(rand()),
        ),

        // oauth
        'oauth_login' => array(
            'category'      => 'oauth',
            'title'         => _a('oAuth login'),
            'description'   => _a('At this moment just support google'),
            'edit'          => 'checkbox',
            'filter'        => 'number_int',
            'value'         => 0
        ),

        'oauth_google_client_id' => array(
            'category'      => 'oauth',
            'title'         => _a('Google client id'),
            'edit'          => 'text',
            'filter'        => 'string',
            'value'         => '',
        ),

        'oauth_google_client_secret' => array(
            'category'      => 'oauth',
            'title'         => _a('Google client secret'),
            'edit'          => 'text',
            'filter'        => 'string',
            'value'         => '',
        ),
    ),
);