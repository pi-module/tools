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
    'social' => [
        'name'        => 'social',
        'title'       => _a('Social network list'),
        'description' => _a('Website official social network list'),
        'render'      => ['block', 'social'],
        'template'    => 'social',
        'config'      => [
            'text-description' => [
                'title'       => _a('Description above list'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => _a('Join to our social networks'),
            ],
            'div-class'        => [
                'title'       => sprintf(_a('Css class for main %s'), _escape('<div>')),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => 'text-center',
            ],
            'ul-class'         => [
                'title'       => sprintf(_a('Css class for main %s'), _escape('<ul>')),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => 'list-inline',
            ],
            'li-class'         => [
                'title'       => sprintf(_a('Css class for main %s'), _escape('<li>')),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => 'list-inline-item text-center',
            ],
        ],
    ],
];