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
 * Version controller
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class VersionController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check token
        if ($config['app_version_active']) {
            // Set default result
            $result = [
                'result' => true,
                'data'   => [
                    'android_version'  => $config['android_version'],
                    'android_url'      => $config['android_url'],
                    'android_is_force' => $config['android_is_force'],
                    'android_message'  => $config['android_message'],
                    'ios_version'      => $config['ios_version'],
                    'ios_url'          => $config['ios_url'],
                    'ios_is_force'     => $config['ios_is_force'],
                    'ios_message'      => $config['ios_message'],
                    'pwa_version'      => $config['pwa_version'],
                    'pwa_url'          => $config['pwa_url'],
                    'pwa_is_force'     => $config['pwa_is_force'],
                    'pwa_message'      => $config['pwa_message'],
                ],
                'error'  => [],
            ];
        } else {
            // Set default result
            $result = [
                'result' => false,
                'data'   => [],
                'error'  => [
                    'code'    => 1,
                    'message' => __('Check version is inactive'),
                ],
            ];
        }

        // Return result
        return $result;
    }
}
