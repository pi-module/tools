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

class SocialForm extends BaseForm
{
    public function __construct($name = null, $options = [])
    {
        $this->options = $options;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SocialFilter($this->options);
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add([
            'name'       => 'id',
            'attributes' => [
                'type' => 'hidden',
            ],
        ]);
        // title
        $this->add([
            'name'       => 'title',
            'options'    => [
                'label' => __('Title'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'required'    => true,
            ],
        ]);
        // slug
        $this->add([
            'name'       => 'slug',
            'options'    => [
                'label' => __('Slug'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('Use direct social network name, for example twitter or facebook, you can add each social network just one time'),
                'required'    => true,
            ],
        ]);
        // url
        $this->add([
            'name'       => 'url',
            'options'    => [
                'label' => __('Url'),
            ],
            'attributes' => [
                'type'        => 'url',
                'description' => '',
                'required'    => true,
            ],
        ]);
        // icon
        $this->add([
            'name'       => 'icon',
            'options'    => [
                'label' => __('Icon'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('Just use http://fontawesome.io/icons website icons and just put icon name, for example fa-home'),
                'required'    => true,
            ],
        ]);
        // order
        $this->add([
            'name'       => 'order',
            'options'    => [
                'label' => __('Sort order'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'required'    => true,
            ],
        ]);
        // status
        $this->add([
            'name'    => 'status',
            'type'    => 'select',
            'options' => [
                'label'         => __('Status'),
                'value_options' => [
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Delete'),
                ],
            ],
        ]);
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Submit'),
            ],
        ]);
    }
}