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

namespace Module\Tools\Block;

use Pi;
use Zend\Db\Sql\Predicate\Expression;

class Block
{
    public static function social($options = [], $module = null)
    {
        // Set options
        $block = [];
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