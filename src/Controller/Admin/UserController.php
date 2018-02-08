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

use Pi;
use Pi\Mvc\Controller\ActionController;
use Zend\Db\Sql\Predicate\Expression;

class UserController extends ActionController
{
    public function exportAction()
    {
        if (!Pi::service('module')->isActive('user')) {
            // Set view
            $this->view()->setTemplate('user-install');
        } else {
            // Get inf0
            $module   = $this->params('module');
            $file     = $this->params('file');
            $page     = $this->params('page', 1);
            $count    = $this->params('count');
            $complete = $this->params('complete', 0);
            $confirm  = $this->params('confirm', 0);

            // Set file
            if (empty($file)) {
                $file = sprintf('user-export-%s-%s', date('Y-m-d-H-i-s'), rand(100, 999));
            }

            // Set path
            $path = Pi::path('upload/tools');
            if (!Pi::service('file')->exists($path . '/index.html')) {
                Pi::service('file')->copy(
                    Pi::path('upload/index.html'),
                    Pi::path('upload/tools/index.html')
                );
            }

            // Set fields
            $fields = array_keys(Pi::registry('field', 'user')->read('', 'display'));

            // Get config
            $config = Pi::service('registry')->config->read($module);
            // Check request
            if ($confirm == 1) {

                // Set file
                Pi::service('audit')->attach('user-export', [
                    'file'   => Pi::path(sprintf('upload/tools/%s.csv', $file)),
                    'format' => 'csv',
                ]);

                $order  = ['id ASC'];
                $where  = ['active' => 1];
                $limit  = 50;
                $offset = (int)($page - 1) * $limit;

                $users = Pi::api('user', 'user')->getList(
                    $where,
                    $limit,
                    $offset,
                    $order,
                    $fields
                );

                // Make list
                foreach ($users as $user) {
                    // Set key
                    if ($complete == 0) {
                        Pi::service('audit')->log('user-export', array_keys($user));
                    }

                    // Set to csv
                    Pi::service('audit')->log('user-export', $user);

                    // Set complete
                    $complete++;
                }

                // Update page
                $page++;

                // Get count
                if (!$count) {
                    $columns = ['count' => new Expression('count(*)')];
                    $select  = Pi::Model('user_account')->select()->columns($columns)->where($where);
                    $count   = Pi::Model('user_account')->selectWith($select)->current()->count + $count;
                }

                // Set complete
                $percent = (100 * $complete) / $count;
                // Set next url
                if ($complete >= $count) {
                    $nextUrl       = '';
                    $downloadAllow = 1;
                } else {
                    $nextUrl       = Pi::url($this->url('', [
                        'action'   => 'export',
                        'page'     => $page,
                        'count'    => $count,
                        'complete' => $complete,
                        'confirm'  => $confirm,
                        'file'     => $file,

                    ]));
                    $downloadAllow = 0;
                }

                $info = [
                    'count'         => $count,
                    'complete'      => $complete,
                    'percent'       => $percent,
                    'nextUrl'       => $nextUrl,
                    'downloadAllow' => $downloadAllow,
                ];

                $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);

                $fileList = '';
            } else {
                // Set info
                $info          = [];
                $percent       = 0;
                $nextUrl       = '';
                $downloadAllow = 0;
                // Set filter
                $filter = function ($fileinfo) {
                    if (!$fileinfo->isFile()) {
                        return false;
                    }
                    $filename = $fileinfo->getFilename();
                    if ('index.html' == $filename) {
                        return false;
                    }
                    return $filename;
                };
                // Get file list
                $fileList = Pi::service('file')->getList($path, $filter);
            }
            // Check convert to excel
            $checkExcel = Pi::api('CSVToExcelConverter', 'tools')->check();
            // Set view
            $this->view()->setTemplate('user-export');
            $this->view()->assign('config', $config);
            $this->view()->assign('nextUrl', $nextUrl);
            $this->view()->assign('downloadAllow', $downloadAllow);
            $this->view()->assign('percent', $percent);
            $this->view()->assign('info', $info);
            $this->view()->assign('confirm', $confirm);
            $this->view()->assign('fileList', $fileList);
            $this->view()->assign('file', $file);
            $this->view()->assign('checkExcel', $checkExcel);
        }
    }

    public function importAction()
    {
        if (!Pi::service('module')->isActive('user')) {
            // Set view
            $this->view()->setTemplate('user-install');
        } else {
            // Get from url
            $addUser = $this->params('addUser');
            // Set file
            $file = Pi::path('upload/tools/user-import.csv');
            // Set user array
            $users = [];
            // Check file
            if (Pi::service('file')->exists($file)) {
                // Set
                $message     = sprintf(__('You can import this information from %s'), $file);
                $countOfUser = 0;
                // Get user meta
                $meta = Pi::registry('field', 'user')->read();
                // Set file users to array
                // from : https://secure.php.net/manual/en/function.fgetcsv.php
                $userData = [];
                $row      = 1;
                if (($handle = fopen($file, "r")) !== false) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        $num = count($data);
                        $i   = 1;
                        for ($c = 0; $c < $num; $c++) {
                            $userData[$row][$i] = $data[$c];
                            $i++;
                        }
                        $row++;
                    }
                    fclose($handle);
                }
                // Make user field list
                $fieldList = array_shift($userData);
                // Make user array and import to DB
                foreach ($userData as $userId => $userInfo) {
                    // Set user values
                    foreach ($userInfo as $key => $field) {
                        if (isset($meta[$fieldList[$key]])) {
                            $users[$userId][$fieldList[$key]] = $field;
                        }
                    }
                    $users[$userId]['last_modified'] = time();
                    $users[$userId]['ip_register']   = Pi::user()->getIp();
                    // Check allow add user by admin
                    if ($addUser == 'OK') {
                        // Check field list
                        $mainFieldList = ['identity', 'credential', 'email', 'first_name', 'last_name', 'mobile'];
                        foreach ($mainFieldList as $mainField) {
                            if (!in_array($mainField, $fieldList)) {
                                $url = ['action' => 'import'];
                                $this->jump($url, sprintf(__('%s field not set'), $mainField), 'error');
                            }
                        }

                        $users[$userId]['name'] = sprintf(
                            '%s %s',
                            $users[$userId]['first_name'],
                            $users[$userId]['last_name']
                        );

                        // Add user
                        $uid = Pi::api('user', 'user')->addUser($users[$userId]);
                        // Check user add or not
                        if ($uid) {
                            // Set user role
                            Pi::api('user', 'user')->setRole($uid, 'member');
                            // Active user
                            $status = Pi::api('user', 'user')->activateUser($uid);
                            if ($status) {
                                // Target activate user event
                                Pi::service('event')->trigger('user_activate', $uid);
                                // Update count
                                $countOfUser++;
                            }
                            // Add credit
                            Pi::api('credit', 'order')->addCredit(
                                $uid,
                                $users[$userId]['credit'],
                                'increase',
                                'automatic',
                                __('Add first credit'),
                                __('Add first credit')
                            );
                        }
                    }
                }
                // Back to index if add user if OK
                if ($addUser == 'OK') {
                    $url = ['action' => 'import'];
                    if ($countOfUser > 0) {
                        $this->jump($url, sprintf(__('%s user added'), $countOfUser));
                    } else {
                        $this->jump($url, __('No user added !'), 'error');
                    }
                }
            } else {
                $message = sprintf(__('user-import.csv not exist on %s'), $file);
            }

            // Set view
            $this->view()->setTemplate('user-import');
            $this->view()->assign('message', $message);
            $this->view()->assign('file', $file);
            $this->view()->assign('users', $users);
            $this->view()->assign('f', Pi::registry('field', 'user')->read());
        }
    }

    public function downloadAction()
    {
        $file = $this->params('file');
        $type = $this->params('type');

        $csvFile   = $file . '.csv';
        $csvPath   = Pi::path('upload/tools/') . $csvFile;
        $excelFile = $file . '.xlsx';
        $excelPath = Pi::path('upload/tools/') . $excelFile;

        switch ($type) {
            case 'xlsx':
                if (Pi::service('file')->exists($csvPath)) {
                    // Check excel file exist
                    if (!Pi::service('file')->exists($excelPath)) {
                        try {
                            Pi::api('CSVToExcelConverter', 'tools')->convert($csvPath, $excelPath);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                    $url = sprintf(
                        '%s?upload/tools/%s',
                        Pi::url('www/script/download.php'),
                        $excelFile);
                }
                break;

            default:
            case 'csv':
                if (Pi::service('file')->exists($csvPath)) {
                    $url = sprintf(
                        '%s?upload/tools/%s',
                        Pi::url('www/script/download.php'),
                        $csvFile);
                }
                break;
        }

        // Set url
        return $this->redirect()->toUrl($url);
    }
}