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

namespace Module\Tools\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Math\Rand;

/*
 * Pi::api('token', 'tools')->getList();
 * Pi::api('token', 'tools')->generate($length, $charList);
 * Pi::api('token', 'tools')->add($uid);
 * Pi::api('token', 'tools')->check($token, $checkUser);
 * Pi::api('token', 'tools')->refresh($token, $uid);
 * Pi::api('token', 'tools')->remove($params);
 */

class Token extends AbstractApi
{
    public function getList()
    {
        // Get info
        $list   = [];
        $order  = ['time_create DESC', 'id DESC'];
        $where  = ['uid' => 0, 'status' => 1];
        $select = Pi::model('token', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('token', $this->getModule())->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id]                   = $row->toArray();
            $list[$row->id]['used_view']      = _number($row->used);
            $list[$row->id]['time_used_view'] = ($row->time_used > 0) ? _date($row->time_used) : __('Not used yet');
        }

        return $list;
    }

    public function generate($uid = 0, $length = 16, $charList = '')
    {
        $length      = ($length > 15) ? $length : 16;
        $sysCharList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ0123456789';
        $charList    = !empty($charList) ? $charList : $sysCharList;
        $string      = Rand::getString($length, $charList, true);
        $string      = ($uid > 0) ? sprintf('user-%s-%s', $uid, $string) : $string;
        return $string;
    }

    public function add($uid)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Generate token
        $token = $this->generate($uid, 32);

        // Save token
        $row              = Pi::model('token', $this->getModule())->createRow();
        $row->uid         = $uid;
        $row->title       = '';
        $row->token       = $token;
        $row->used        = 0;
        $row->time_create = time();
        $row->time_used   = 0;
        $row->time_expire = time() + $config['valid_time'] * 60;
        $row->status      = 1;
        $row->save();

        // return token
        return $token;
    }

    public function check($token, $checkUser = false)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Get token
        $token = Pi::model('token', $this->getModule())->find($token, 'token');

        // Check token exist
        if (!$token) {
            return [
                'status'  => 0,
                'code'    => 1,
                'message' => __('Token is not valid !'),
            ];
        }

        // Check token active
        if ($token->status != 1) {
            return [
                'status'  => 0,
                'code'    => 2,
                'message' => __('Token is not active !'),
            ];
        }

        // Check user
        if ($token->uid > 0) {
            if ($token->time_expire < time()) {
                return [
                    'status'  => 0,
                    'code'    => 3,
                    'message' => __('This token is expire'),
                ];
            }
        } elseif ($token->uid == 0 && $checkUser) {
            return [
                'status'  => 0,
                'code'    => 4,
                'message' => __('This token not connect for any user'),
            ];
        }

        // Update information
        $token->time_used   = time();
        $token->used        = $token->used + 1;
        $token->time_expire = time() + $config['valid_time'] * 60;
        $token->save();

        // return result
        return [
            'status'      => 1,
            'uid'         => $token->uid,
            'time_expire' => $token->time_expire,
            'message'     => __('Token is valid !'),
        ];
    }

    public function refresh($token, $uid)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Get token
        $token = Pi::model('token', $this->getModule())->find($token, 'token');

        // Check token exist
        if (!$token) {
            return [
                'status'  => 0,
                'code'    => 1,
                'message' => __('Token is not valid !'),
            ];
        }

        // Check token active
        if ($token->status != 1) {
            return [
                'status'  => 0,
                'code'    => 2,
                'message' => __('Token is not active !'),
            ];
        }

        // Check token exist
        if ($token->uid != $uid || $uid == 0) {
            return [
                'status'  => 0,
                'code'    => 3,
                'message' => __('This is not your token !'),
            ];
        }

        // Check token exist
        if ($token->time_expire > time()) {
            return [
                'status'  => 0,
                'code'    => 4,
                'message' => __('Your token is not expire yet !'),
            ];
        }

        // Refresh
        $token->time_expire = time() + $config['valid_time'] * 60;
        $token->save();

        // return result
        return [
            'status'  => 1,
            'message' => __('Token refreshed !'),
        ];
    }

    public function remove($params)
    {
        if (isset($params['uid']) && $params['uid'] > 0) {
            Pi::model('token', $this->getModule())->delete(
                [
                    'uid' => $params['uid'],
                ]
            );
        } elseif (isset($params['token'])) {
            Pi::model('token', $this->getModule())->delete(
                [
                    'token' => $params['token'],
                ]
            );
        } elseif (isset($params['expire_days'])) {
            Pi::model('token', $this->getModule())->delete(
                [
                    'time_expire < ?' => time() - ($params['expire_days'] * 86400),
                ]
            );
        }
    }
}