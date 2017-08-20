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

namespace Module\Tools\Form;

use Pi;
use Zend\InputFilter\InputFilter;

class TokenFilter extends InputFilter
{
    public function __construct($options = array())
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // token
        $this->add(array(
            'name' => 'token',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Tools\Validator\TokenDuplicate,
            ),
        ));
        // use_module
        $this->add(array(
            'name' => 'use_module',
            'required' => false,
        ));
        // use_section
        /* $this->add(array(
            'name' => 'use_section',
            'required' => false,
        )); */
        // status
        $this->add(array(
            'name' => 'status',
            'required' => false,
        ));
    }
}