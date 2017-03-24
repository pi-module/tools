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

class TokenForm extends BaseForm
{
    public function __construct($name = null, $options = array())
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
        // token
        $this->add(array(
            'name' => 'token',
            'options' => array(
                'label' => __('Token'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // use_module
        $moduleList = array();
        $modules = Pi::registry('modulelist')->read('active');
        foreach ($modules as $module) {
            $moduleList[$module['name']] = $module['title'];
        }
        $this->add(array(
            'name' => 'use_module',
            'type' => 'select',
            'options' => array(
                'label' => __('Module'),
                'value_options' => $moduleList,
            ),
        ));
        // use_section
        $this->add(array(
            'name' => 'use_section',
            'type' => 'select',
            'options' => array(
                'label' => __('Module section'),
                'value_options' => array(
                    'general' => __('General API'),
                    'api' => __('External API'),
                    'user' => __('External API for login user'),
                    'server' => __('Server API'),
                    'system' => __('System API'),
                ),
            ),
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