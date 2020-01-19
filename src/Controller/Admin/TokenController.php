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
        $where  = ['uid' => 0, 'status' => 1];
        $select = $this->getModel('token')->select()->where($where)->order($order);
        $rowset = $this->getModel('token')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $list[$row->id]                   = $row->toArray();
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

        // Set option
        $options = [
            'id' => $id,
        ];

        // Set form
        $form = new TokenForm('token', $options);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new TokenFilter($options));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('token')->find($id);
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