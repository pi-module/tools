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
            'title' => _a('Custom'),
            'name'  => 'custom',
        ],
        [
            'title' => _a('User'),
            'name'  => 'user',
        ],
        [
            'name'  => 'cron',
            'title' => _t('Cron'),
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
            'value'       => 15,
            'category'    => 'token',
        ],

        'auto_refresh' => [
            'category'    => 'token',
            'title'       => _a('Auto refresh'),
            'description' => _a('Refresh token in check method if just token time expire'),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
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

        // User
        'fields'       => [
            'title'       => _a('Fields'),
            'description' => _a('User fields for login and profile'),
            'edit'        => 'textarea',
            'value'       => 'first_name,last_name,id_number,phone,mobile,address1,address2,country,state,city,zip_code',
            'category'    => 'home',
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

        'cron_token' => [
            'category' => 'cron',
            'title'    => _a('Cron token'),
            'edit'     => 'text',
            'filter'   => 'string',
            'value'    => md5(rand()),
        ],
    ],
];
