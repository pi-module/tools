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
    'category' => [
        [
            'title' => _a('Admin'),
            'name'  => 'admin',
        ],
        [
            'title' => _a('Token'),
            'name'  => 'token',
        ],
        [
            'title' => _a('App version'),
            'name'  => 'app_version',
        ],
        [
            'title' => _a('User'),
            'name'  => 'user',
        ],
        [
            'name'  => 'cron',
            'title' => _t('Cron'),
        ],
        [
            'title' => _a('Custom API'),
            'name'  => 'custom',
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

        // Token
        'valid_time'    => [
            'title'       => _a('User token valid time'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        15    => _a('15 min'),
                        30    => _a('30 min'),
                        60    => _a('1 hour'),
                        360   => _a('6 hours'),
                        720   => _a('12 hours'),
                        1440  => _a('1 day'),
                        10080 => _a('7 days'),
                        20160 => _a('14 days'),
                        43200 => _a('30 days'),
                    ],
                ],
            ],
            'filter'      => 'number_int',
            'value'       => 20160,
            'category'    => 'token',
        ],

        'auto_refresh' => [
            'category'    => 'token',
            'title'       => _a('Auto refresh'),
            'description' => _a('Refresh token in check method if just token time expire'),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],

        // app_version
        'app_version_active'  => [
            'category'    => 'app_version',
            'title'       => _a('Active App version'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'android_version' => [
            'category'    => 'app_version',
            'title'       => _a('Android, Latest version'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],

        'android_url' => [
            'category'    => 'app_version',
            'title'       => _a('Android, app URL'),
            'description' => _a('For example Google Play url, or custom APK url'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],

        'android_message' => [
            'category'    => 'app_version',
            'title'       => _a('Android, message'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => _a('New version of application is available, please update your version'),
        ],

        'android_is_force' => [
            'category'    => 'app_version',
            'title'       => _a('Android, is force update?'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'ios_version' => [
            'category'    => 'app_version',
            'title'       => _a('IOS, Latest version'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],

        'ios_url' => [
            'category'    => 'app_version',
            'title'       => _a('IOS, app URL'),
            'description' => _a('For example Google Play url, or custom APK url'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],

        'ios_message' => [
            'category'    => 'app_version',
            'title'       => _a('IOS, app URL'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => _a('New version of application is available, please update your version'),
        ],

        'ios_is_force' => [
            'category'    => 'app_version',
            'title'       => _a('IOS, is force update?'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],



        'pwa_version' => [
            'category'    => 'app_version',
            'title'       => _a('PWA, Latest version'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],

        'pwa_url' => [
            'category'    => 'app_version',
            'title'       => _a('PWA, app URL'),
            'description' => _a('For example Google Play url, or custom APK url'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],

        'pwa_is_force' => [
            'category'    => 'app_version',
            'title'       => _a('PWA, is force update?'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        'pwa_message' => [
            'category'    => 'app_version',
            'title'       => _a('PWA, app URL'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => _a('New version of application is available, please update your version'),
        ],

        // User
        'fields'       => [
            'title'       => _a('Fields'),
            'description' => _a('User fields for login and profile'),
            'edit'        => 'textarea',
            'value'       => 'first_name,last_name,id_number,phone,mobile,address1,address2,country,state,city,zip_code',
            'category'    => 'user',
        ],

        'force_mobile'  => [
            'title'       => _a('Force use mobile number'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
            'category'    => 'user',
        ],

        'roles'       => [
            'title'       => _a('Roles'),
            'description' => _a('User roles assign in register'),
            'edit'        => 'textarea',
            'value'       => '',
            'category'    => 'user',
        ],

        // Custom
        'custom_token' => [
            'title'       => _a('Custom token type'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        false => _a('Support both of user and static tokens'),
                        true  => _a('Just user token allowed'),
                    ],
                ],
            ],
            'filter'      => 'string',
            'value'       => true,
            'category'    => 'custom',
        ],

        // Cron
        'cron_active'  => [
            'category'    => 'cron',
            'title'       => _a('Active cron'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
    ],
];
