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
use Pi\Authentication\Result;
use Pi\Mvc\Controller\ActionController;
use Module\System\Validator\UserEmail as UserEmailValidator;
use Module\System\Validator\Username as UsernameValidator;
use Module\Tools\Validator\Mobile as UserMobileValidator;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class CustomController extends ActionController
{
    public function indexAction()
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

        // Get config
        $config = Pi::service('registry')->config->read('tools');

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, $config['custom_token']);
        if ($check['status'] == 1) {

            // Get info from url
            $slug = $this->params('slug');

            // Get custom
            $custom = Pi::api('custom', 'tools')->get($slug, 'slug');

            // Check
            if ($custom['status'] == 1) {

                // Set default result
                $result = [
                    'result' => true,
                    'data'   => [
                        $custom['items'],
                    ],
                    'error'  => [
                        'code'    => 0,
                        'message' => $config['custom_token'],
                    ],
                ];
            } else {
                // Set error
                $result['error'] = [
                    'code'    => $custom['code'],
                    'message' => $custom['message'],
                ];
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }
}
