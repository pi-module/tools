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
 * Pi::api('token', 'tools')->getList($module);
 * Pi::api('token', 'tools')->check($token, $module, $section);
 */

class Token extends AbstractApi
{
    public function generate($length = 16, $charlist = '')
    {
        $length         = ($length > 15) ? $length : 16;
        $systemCharList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ0123456789';
        $charlist       = !empty($charlist) ? $charlist : $systemCharList;
        $string         = Rand::getString($length, $charlist, true);
        return $string;
    }

    public function getList($module, $section = '')
    {
        // Get info
        $list  = [];
        $order = ['time_create DESC', 'id DESC'];
        $where = ['use_module' => $module, 'status' => 1];
        /* if (!empty($section)) {
            $where['use_section'] = $section;
        } */
        $select = Pi::model('token', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('token', $this->getModule())->selectWith($select);
        // Get module list
        $modules = Pi::registry('modulelist')->read('active');
        // Make list
        foreach ($rowset as $row) {
            /* switch ($row->use_section) {
                case 'general':
                    $section = __('General API');
                    break;

                case 'api':
                    $section = __('External API');
                    break;

                case 'user':
                    $section = __('External API for login user');
                    break;

                case 'server':
                    $section = __('Server API');
                    break;

                case 'system':
                    $section = __('System API');
                    break;
            } */
            $list[$row->id]                    = $row->toArray();
            $list[$row->id]['use_module_view'] = $modules[$row->use_module]['title'];
            // $list[$row->id]['use_section_view'] = $section;
            $list[$row->id]['used_view']      = _number($row->used);
            $list[$row->id]['time_used_view'] = ($row->time_used > 0) ? _date($row->time_used) : __('Not used yet');
        }

        return $list;
    }

    public function check($token, $module, $section = '')
    {
        $token = Pi::model('token', $this->getModule())->find($token, 'token');
        // Check token exist
        if (!$token) {
            return [
                'status'  => 0,
                'message' => __('Token is not valid !'),
            ];
        }
        // Check token active
        if ($token->status != 1) {
            return [
                'status'  => 0,
                'message' => __('Token is not active !'),
            ];
        }
        // Check module and section is set true
        if ($token->use_module != $module) {
            return [
                'status'  => 0,
                'message' => __('This token is not for this part !'),
            ];
        }
        // Check module and section is set true
        /* if ($token->use_section != $section) {
            return array(
                'status' => 0,
                'message' => __('This token is not for this part !')
            );
        } */
        // Update information
        $token->time_used = time();
        $token->used      = $token->used + 1;
        $token->save();
        // return result
        return [
            'status'  => 1,
            'message' => __('Token is valid !'),
        ];
    }
}