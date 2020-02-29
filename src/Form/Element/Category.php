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

namespace Module\Tools\Form\Element;

use Pi;
use Zend\Form\Element\Select;

class Category extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $list    = [];
            $columns = ['id', 'title'];
            $where   = ['status' => 1];
            $order   = ['title ASC', 'id DESC'];
            $select  = Pi::model('category', 'tools')->select()->columns($columns)->where($where)->order($order);
            $rowset  = Pi::model('category', 'tools')->selectWith($select);
            foreach ($rowset as $row) {
                $list[$row->id] = $row->title;
            }
            $this->valueOptions = $list;
        }
        return $this->valueOptions;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->Attributes = [
            'size'     => 1,
            'multiple' => 0,
            'class'    => 'form-control',
        ];

        // check form size
        if (isset($this->attributes['size'])) {
            $this->Attributes['size'] = $this->attributes['size'];
        }

        // check form multiple
        if (isset($this->attributes['multiple'])) {
            $this->Attributes['multiple'] = $this->attributes['multiple'];
        }

        return $this->Attributes;
    }
}