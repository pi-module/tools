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

namespace Module\Tools\Form;

use Pi;
use Zend\InputFilter\InputFilter;

class ItemFilter extends InputFilter
{
    public function __construct($options = [])
    {
        // category
        $this->add(
            [
                'name'     => 'category',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // title
        $this->add(
            [
                'name'     => 'title',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // key
        $this->add(
            [
                'name'       => 'key',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Tools\Validator\KeyDuplicate(
                        [
                            'id' => $options['id'],
                        ]
                    ),
                ],
            ]
        );

        // value
        $this->add(
            [
                'name'     => 'value',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // Status
        $this->add(
            [
                'name'     => 'status',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
    }
}