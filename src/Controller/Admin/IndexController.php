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

namespace Module\Tools\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $subject = _('Welcome to Pi community');

        $body = _('Mail body message with HTML ...');
        ;
        $messageHtml = Pi::service('mail')->message($subject, $body, 'text/html');


        // Send with specified transport
        $transport = Pi::service('mail')->loadTransport(
            'smtp',
            [
                'username' => 'AKIARH4VNKAKYLCQJDPX',
                'password' => 'BF+kftWUifL8xORdRwa8ZczroZI+FiE1AIBhaK9PGplO',
            ]
        );
        $transport->send($messageHtml);


        return $this->redirect()->toRoute(
            '',
            [
                'controller' => 'token',
                'action'     => 'index',
            ]
        );
    }
}
