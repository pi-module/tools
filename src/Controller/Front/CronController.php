<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

namespace Module\Tools\Controller\Front;

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
        // Set template
        $this->view()->setTemplate(false)->setLayout('layout-content');
        // Get info from url
        $module = $this->params('module');
        $token  = $this->params('token');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get token
        if ($config['cron_active'] && !empty($config['cron_token']) && $token == $config['cron_token']) {
            // Do cron
            Pi::service('cron')->start();
            // return
            return [
                'message' => __('Cron system work fine, and cron process finished successfully.'),
                'status'  => 1,
                'time'    => time(),
            ];
        } else {
            return [
                'message' => __('Error : token not true !'),
                'status'  => 0,
                'time'    => time(),
            ];
        }
    }
}