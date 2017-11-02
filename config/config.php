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
    'category' => [
        [
            'title' => _a('Admin'),
            'name'  => 'admin',
        ],
        [
            'name'  => 'cron',
            'title' => _t('Cron'),
        ],
        [
            'name'  => 'oauth',
            'title' => _t('oAuth'),
        ],
    ],
    'item'     => [
        // Admin
        'admin_perpage' => [
            'category'    => 'admin',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 25,
        ],

        // Cron
        'cron_active'   => [
            'category'    => 'cron',
            'title'       => _a('Active cron'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'cron_token'  => [
            'category' => 'cron',
            'title'    => _a('Cron token'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => md5(rand()),
        ],

        // oauth
        'oauth_login' => [
            'category'    => 'oauth',
            'title'       => _a('oAuth login'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'oauth_google' => [
            'category'    => 'oauth',
            'title'       => _a('Login by google'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'oauth_google_client_id' => [
            'category' => 'oauth',
            'title'    => _a('Google client id'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_google_client_secret' => [
            'category' => 'oauth',
            'title'    => _a('Google client secret'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_twitter' => [
            'category'    => 'oauth',
            'title'       => _a('Login by twitter'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'oauth_twitter_api_key' => [
            'category' => 'oauth',
            'title'    => _a('Twitter consumer Key (API Key)'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_twitter_api_secret' => [
            'category' => 'oauth',
            'title'    => _a('Twitter consumer Secret (API Secret)'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_facebook' => [
            'category'    => 'oauth',
            'title'       => _a('Login by facebook'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'oauth_facebook_api_id' => [
            'category' => 'oauth',
            'title'    => _a('Facebook app ID'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_facebook_api_secret' => [
            'category' => 'oauth',
            'title'    => _a('Facebook app Secret'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_github' => [
            'category'    => 'oauth',
            'title'       => _a('Login by github'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'oauth_github_client_id' => [
            'category' => 'oauth',
            'title'    => _a('Github client id'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],

        'oauth_github_client_secret' => [
            'category' => 'oauth',
            'title'    => _a('Github client secret'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => '',
        ],
    ],
];