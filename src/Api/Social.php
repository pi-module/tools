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

/*
 * Pi::api('social', 'tools')->getList();
 * Pi::api('social', 'tools')->canonizeSocial($social);
 */

class Social extends AbstractApi
{
    public function getList()
    {
        $list = array();
        $where = array('status' => 1);
        $order = array('order ASC', 'id DESC');
        $select = Pi::model('social', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('social', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $this->canonizeSocial($row);
        }
        return $list;
    }

    public function canonizeSocial($social)
    {
        // Check
        if (empty($social)) {
            return '';
        }
        // object to array
        $social = $social->toArray();

        return $social;
    }
}