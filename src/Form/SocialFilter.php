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

class SocialFilter extends InputFilter
{
    public function __construct($options = [])
    {
        // id
        $this->add([
            'name'     => 'id',
            'required' => false,
        ]);
        // title
        $this->add([
            'name'     => 'title',
            'required' => true,
            'filters'  => [
                [
                    'name' => 'StringTrim',
                ],
            ],
        ]);
        // slug
        $this->add([
            'name'       => 'slug',
            'required'   => true,
            'filters'    => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                new \Module\Tools\Validator\SocialSlugDuplicate,
            ],
        ]);
        // url
        $this->add([
            'name'       => 'url',
            'required'   => true,
            'filters'    => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                new \Module\Tools\Validator\SocialUrlDuplicate,
            ],
        ]);
        // icon
        $this->add([
            'name'     => 'icon',
            'required' => true,
            'filters'  => [
                [
                    'name' => 'StringTrim',
                ],
            ],
        ]);
        // order
        $this->add([
            'name'     => 'order',
            'required' => true,
        ]);
        // status
        $this->add([
            'name'     => 'status',
            'required' => false,
        ]);
    }
}