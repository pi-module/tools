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
 * Pi::api('custom', 'tools')->get($parameter, $type);
 */

class Custom extends AbstractApi
{
    public function get($parameter, $type = 'id')
    {
        $category = Pi::model('category', $this->getModule())->find($parameter, $type);
        $category = $this->canonizeCategory($category);
        return $category;
    }

    public function canonizeCategory($category)
    {
        // Check
        if (empty($category)) {
            return [
                'status'  => 0,
                'message' => __('Selected category does not exist !'),
            ];
        }

        // object to array
        $category = $category->toArray();

        // Check
        if ($category['status'] != 1) {
            return [
                'status'  => 0,
                'message' => __('Category not active'),
            ];
        }

        // Set status
        $category['status'] = 1;

        // Get items
        $category['items'] = $this->getItemsByCategory($category);

        // Return result
        return $category;
    }

    public function getItemsByCategory($category)
    {
        // Get info
        $list   = [];
        $order  = ['id DESC'];
        $where  = ['category' => $category['id'], 'status' => 1];
        $select = Pi::model('item', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('item', $this->getModule())->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $list[$row->key] = $row->value;
        }

        return $list;
    }

}