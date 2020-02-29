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

class CategoryFilter extends InputFilter
{
    public function __construct($options = [])
    {
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

        // slug
        $this->add(
            [
                'name'       => 'slug',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Tools\Validator\SlugDuplicate(
                        [
                            'id' => $options['id'],
                        ]
                    ),
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