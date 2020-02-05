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
    // Module meta
    'meta'     => [
        'title'       => _a('Tools'),
        'description' => _a('Admin tools for pi engine'),
        'version'     => '0.3.1',
        'license'     => 'New BSD',
        'logo'        => 'image/logo.png',
        'readme'      => 'docs/readme.txt',
        'demo'        => 'http://piengine.org',
        'icon'        => 'fa-wrench',
    ],
    // Author information
    'author'   => [
        'Name'    => 'Hossein Azizabadi',
        'email'   => 'azizabadi@faragostaresh.com',
        'website' => 'http://piengine.org',
        'credits' => 'Pi Engine Team',
    ],
    // Resource
    'resource' => [
        'database'   => 'database.php',
        'config'     => 'config.php',
        'permission' => 'permission.php',
        'page'       => 'page.php',
        'navigation' => 'navigation.php',
    ],
];
