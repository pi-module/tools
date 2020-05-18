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

namespace Module\Tools\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Math\Rand;

/*
 * Pi::api('user', 'tools')->updateDeviceToken($uid, $deviceToken);
 */

class User extends AbstractApi
{
    public function updateDeviceToken($uid, $deviceToken)
    {
        Pi::model('profile', 'user')->update(
            ['device_token' => $deviceToken],
            ['uid' => $uid]
        );
    }
}
