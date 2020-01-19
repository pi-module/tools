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

        // Cron
        'cron_active'   => [
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