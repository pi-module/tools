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
namespace Module\Tools\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Math\Rand;

/*
 * Pi::api('token', 'tools')->generate($length, $charlist);
 * Pi::api('token', 'tools')->check();
 */

class Token extends AbstractApi
{
    public function generate($length = 128, $charlist = '')
    {
        $length = ($length > 63) ? $length : 128;
        $charlist = !empty($charlist) ? $charlist : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ0123456789';
        $string = Rand::getString($length, $charlist, true);
        return $string;
    }

    public function check($token, $module, $section)
    {
        $token = Pi::model('token', $this->getModule())->find($token, 'token');
        // Check token exist
        if (!$token) {
            return array(
                'status' => 0,
                'message' => __('Token is not valid !')
            );
        }
        // Check token active
        if ($token->status != 1) {
            return array(
                'status' => 0,
                'message' => __('Token is not active !')
            );
        }
        // Check module and section is set true
        if ($token->use_module != $module) {
            return array(
                'status' => 0,
                'message' => __('This token is not for this part !')
            );
        }
        // Check module and section is set true
        if ($token->use_section != $section) {
            return array(
                'status' => 0,
                'message' => __('This token is not for this part !')
            );
        }
        // Update information
        $token->time_used = time();
        $token->used = $token->used + 1;
        $token->save();
        // return result
        return array(
            'status' => 1,
            'message' => __('Token is valid !')
        );
    }
}