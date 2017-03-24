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
    ),
);