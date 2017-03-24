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
namespace Module\Tools\Block;

use Pi;
use Zend\Db\Sql\Predicate\Expression;

class Block
{
    public static function social($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Get list of social
        $block['list'] = Pi::registry('socialList', 'tools')->read();
        // Check list not empty
        if (empty($block['list'])) {
            return false;
        }

        return $block;
    }
}