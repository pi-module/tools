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

class TokenForm extends BaseForm
{
    public function __construct($name = null, $options = [])
    {
        $this->options = $options;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new TokenFilter($this->options);
        }
        return $this->filter;
    }

    public function init()
    {
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

        // token
        $this->add(
            [
                'name'       => 'token',
                'options'    => [
                    'label' => __('Token'),
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
