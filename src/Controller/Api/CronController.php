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
 * Cron controller
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class CronController extends ActionController
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

        // Get info from url
        $module = $this->params('module');
        $token  = $this->params('token');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check token
        $check = Pi::api('token', 'tools')->check($token, $module);
        if ($config['cron_active'] && $check['status'] == 1) {
            Pi::service('cron')->start();

            // Set default result
            $result = [
                'result' => true,
                'data'   => [],
                'error'  => [],
            ];
        } else {
            // Set error
            $result['error'] = [
                'code'    => 2,
                'message' => $check['message'],
            ];
        }

        // Return result
        return $result;
    }
}
