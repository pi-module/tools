<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt BSD 3-Clause License
 * @package         Registry
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Tools\Registry;

use Pi;
use Pi\Application\Registry\AbstractRegistry;

class SocialList extends AbstractRegistry
{
    /** @var string Module name */
    protected $module = 'tools';

    /**
     * {@inheritDoc}
     */
    protected function loadDynamic($options = [])
    {
        $return = [];
        $where  = ['status' => 1];
        $order  = ['order ASC', 'id DESC'];
        $select = Pi::model('social', $this->module)->select()->where($where)->order($order);
        $rowset = Pi::model('social', $this->module)->selectWith($select);
        foreach ($rowset as $row) {
            $return[$row->id] = Pi::api('social', 'tools')->canonizeSocial($row);
        }
        return $return;
    }

    /**
     * {@inheritDoc}
     * @param array
     */
    public function read()
    {
        $options = [];
        $result  = $this->loadData($options);

        return $result;
    }

    /**
     * {@inheritDoc}
     * @param bool $name
     */
    public function create()
    {
        $this->clear('');
        $this->read();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespace($meta = '')
    {
        return parent::setNamespace('');
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->clear('');
    }
}