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

class TokenFilter extends InputFilter
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

        // token
        $this->add(
            [
                'name'       => 'token',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Tools\Validator\TokenDuplicate(
                        [
                            'id' => $options['id'],
                        ]
                    ),
                ],
            ]
        );

        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => false,
            ]
        );
    }
}