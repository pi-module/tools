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
use Pi\Form\Form as BaseForm;

class ItemForm extends BaseForm
{
    public function __construct($name = null, $options = [])
    {
        $this->options = $options;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ItemFilter($this->options);
        }
        return $this->filter;
    }

    public function init()
    {
        // category
        $this->add(
            [
                'name'       => 'category',
                'type'       => 'Module\Tools\Form\Element\Category',
                'options'    => [
                    'label'    => __('Category'),
                    'category' => '',
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // title
        $this->add(
            [
                'name'       => 'title',
                'options'    => [
                    'label' => __('Title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // key
        $this->add(
            [
                'name'       => 'key',
                'options'    => [
                    'label' => __('Key'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // value
        $this->add(
            [
                'name'       => 'value',
                'options'    => [
                    'label' => __('Value'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // Status
        $this->add(
            [
                'name'       => 'status',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Activate'),
                        0 => __('Deactivate'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value'    => 1,
                    'required' => true,
                ],
            ]
        );


        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}
