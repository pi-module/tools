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

namespace Module\Tools\Controller\Admin;

use Module\Tools\Form\TokenFilter;
use Module\Tools\Form\TokenForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class TokenController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list   = [];
        $order  = ['time_create DESC', 'id DESC'];
        $select = $this->getModel('token')->select()->order($order);
        $rowset = $this->getModel('token')->selectWith($select);
        // Get module list
        $modules = Pi::registry('modulelist')->read('active');
        // Make list
        foreach ($rowset as $row) {
            /* switch ($row->use_section) {
                case 'general':
                    $section = __('General API');
                    break;

                case 'api':
                    $section = __('External API');
                    break;

                case 'user':
                    $section = __('External API for login user');
                    break;

                case 'server':
                    $section = __('Server API');
                    break;

                case 'system':
                    $section = __('System API');
                    break;
            } */
            $list[$row->id]                    = $row->toArray();
            $list[$row->id]['use_module_view'] = $modules[$row->use_module]['title'];
            // $list[$row->id]['use_section_view'] = $section;
            $list[$row->id]['used_view']      = _number($row->used);
            $list[$row->id]['time_used_view'] = ($row->time_used > 0) ? _date($row->time_used) : __('Not used yet');
        }
        // Set view
        $this->view()->setTemplate('token-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        // Set form
        $form = new TokenForm('token');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new TokenFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('token')->find($values['id']);
                } else {
                    $row = $this->getModel('token')->createRow();
                }
                $row->assign($values);
                $row->save();
                // jump
                $message = __('Token data saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            if ($id) {
                $token = $this->getModel('token')->find($id)->toArray();
            } else {
                $token          = [];
                $token['token'] = Pi::api('token', 'tools')->generate();
            }
            $form->setData($token);
        }
        // Set view
        $this->view()->setTemplate('token-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Manage token'));
    }
}