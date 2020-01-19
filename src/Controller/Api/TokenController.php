<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

namespace Module\Tools\Controller\Api;

use Pi;
use Pi\Mvc\Controller\ActionController;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class TokenController extends ActionController
{
    public function refreshAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');
        $uid   = $this->params('uid');

        // Check token
        $refresh = Pi::api('token', 'tools')->refresh($token, $uid);
        if ($refresh['status'] == 1) {
            $result = [
                'result' => true,
                'data'   => $refresh,
                'error'  => [],
            ];
        } else {
            $result['error']['code']    = $refresh['code'];
            $result['error']['message'] = $refresh['message'];
        }

        // Return result
        return $result;
    }
}