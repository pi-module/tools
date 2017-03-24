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
namespace Module\Tools\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

class CronController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set cron url
        $cronUrl = Pi::url($this->url('default', array(
            'module'      => 'tools',
            'controller'  => 'cron',
            'action'      => 'index',
            'token'       => $config['cron_token'],
        )));
        // Set template
        $this->view()->setTemplate('cron-index');
        $this->view()->assign('cronUrl', $cronUrl);
        $this->view()->assign('cronActive', $config['cron_active']);
        $this->view()->assign('cronToken', $config['cron_token']);
    }
}