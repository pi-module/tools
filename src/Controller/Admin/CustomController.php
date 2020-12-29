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

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Tools\Form\CategoryFilter;
use Module\Tools\Form\CategoryForm;
use Module\Tools\Form\ItemFilter;
use Module\Tools\Form\ItemForm;

class CustomController extends ActionController
{
    public function categoryAction()
    {
        // Get info
        $list   = [];
        $order  = ['id DESC'];
        $select = $this->getModel('category')->select()->order($order);
        $rowSet = $this->getModel('category')->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->toArray();
        }

        // Set view
        $this->view()->setTemplate('custom-category');
        $this->view()->assign('list', $list);
    }

    public function categoryUpdateAction()
    {
        // Get id
        $id = $this->params('id');

        // Set option
        $options = [
            'id' => $id,
        ];

        // Set form
        $form = new CategoryForm('category', $options);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new CategoryFilter($options));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('category')->find($id);
                } else {
                    $row = $this->getModel('category')->createRow();
                }
                $row->assign($values);
                $row->save();

                // jump
                $message = __('Category data saved successfully.');
                $this->jump(['action' => 'category'], $message);
            }
        } else {
            if ($id) {
                $category = $this->getModel('category')->find($id)->toArray();
                $form->setData($category);
            }
        }

        // Set view
        $this->view()->setTemplate('custom-category-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Manage category'));
    }

    public function itemAction()
    {
        // Get info
        $list   = [];
        $order  = ['id DESC'];
        $select = $this->getModel('item')->select()->order($order);
        $rowSet = $this->getModel('item')->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->toArray();
        }

        // Set view
        $this->view()->setTemplate('custom-item');
        $this->view()->assign('list', $list);
    }

    public function itemUpdateAction()
    {
        // Get id
        $id = $this->params('id');

        // Set option
        $options = [
            'id' => $id,
        ];

        // Set form
        $form = new ItemForm('item', $options);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ItemFilter($options));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('item')->find($id);
                } else {
                    $row = $this->getModel('item')->createRow();
                }
                $row->assign($values);
                $row->save();

                // jump
                $message = __('Item data saved successfully.');
                $this->jump(['action' => 'item'], $message);
            }
        } else {
            if ($id) {
                $item = $this->getModel('item')->find($id)->toArray();
                $form->setData($item);
            }
        }

        // Set view
        $this->view()->setTemplate('custom-item-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Manage item'));
    }
}
