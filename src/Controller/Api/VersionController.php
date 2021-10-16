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
        $platform = $this->params('platform');
        $version = $this->params('version');

        // Check
        $platform = _escape(strtolower($platform));
        $version = (int) $version;

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check token
        if ($config['app_version_active']) {

            // Check and set platform
            switch ($platform) {
                case 'android':
                    $data = [
                        'version'  => $config['android_version'],
                        'url'      => $config['android_url'],
                        'is_force' => $config['android_is_force'],
                        'message'  => $config['android_message'],
                    ];
                    break;

                case 'ios':
                    $data = [
                        'version'      => $config['ios_version'],
                        'url'          => $config['ios_url'],
                        'is_force'     => $config['ios_is_force'],
                        'message'      => $config['ios_message'],
                    ];
                    break;

                case 'pwa':
                    $data = [
                        'version'      => $config['pwa_version'],
                        'url'          => $config['pwa_url'],
                        'is_force'     => $config['pwa_is_force'],
                        'message'      => $config['pwa_message'],
                    ];
                    break;

                default:
                    $data = [
                        'version'  => '',
                        'url'      => '',
                        'is_force' => '',
                        'message'  => '',
                    ];
                    break;
            }

            // Set default result
            $result = [
                'result' => true,
                'data'   => $data,
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
