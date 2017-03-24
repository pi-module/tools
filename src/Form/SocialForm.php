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
use Pi\Form\Form as BaseForm;

class SocialForm extends BaseForm
{
    public function __construct($name = null, $options = array())
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
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // slug
        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => __('Slug'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Use direct social network name, for example twitter or facebook, you can add each social network just one time'),
                'required' => true,
            )
        ));
        // url
        $this->add(array(
            'name' => 'url',
            'options' => array(
                'label' => __('Url'),
            ),
            'attributes' => array(
                'type' => 'url',
                'description' => '',
                'required' => true,
            )
        ));
        // icon
        $this->add(array(
            'name' => 'icon',
            'options' => array(
                'label' => __('Icon'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Just use http://fontawesome.io/icons website icons and just put icon name, for example fa-home'),
                'required' => true,
            )
        ));
        // order
        $this->add(array(
            'name' => 'order',
            'options' => array(
                'label' => __('Sort order'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Delete'),
                ),
            ),
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}