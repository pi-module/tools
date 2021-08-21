<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

namespace Module\Tools\Controller\Api;

use Pi;
use Pi\Authentication\Result;
use Pi\Mvc\Controller\ActionController;
use Pi\File\Transfer\Upload;
use Module\System\Validator\UserEmail as UserEmailValidator;
use Module\System\Validator\Username as UsernameValidator;
use Module\Tools\Validator\Mobile as UserMobileValidator;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class UserController extends ActionController
{
    public function checkAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Load language
            Pi::service('i18n')->load(['module/user', 'default']);

            // Set user fields
            $fields = ['id', 'identity', 'name', 'email', 'first_name', 'last_name', 'device_token'];

            // Get user
            $user = Pi::user()->get($check['uid'], $fields);

            // Get user roles
            $roles = Pi::service('user')->getRole($check['uid'], 'front');

            // Set default result
            $result = [
                'result' => true,
                'data'   => [
                    [
                        'check'        => 1,
                        'uid'          => $user['id'],
                        'identity'     => $user['identity'],
                        'email'        => $user['email'],
                        'name'         => $user['name'],
                        'first_name'   => $user['first_name'],
                        'last_name'    => $user['last_name'],
                        'device_token' => $user['device_token'],
                        'avatar'       => Pi::service('user')->avatar($user['id'], 'xlarge', false) . '?' . time(),
                        'roles'        => $roles,
                    ],
                ],
                'error'  => [
                    'code'    => 0,
                    'message' => '',
                ],
            ];
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function refreshAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');
        $uid   = $this->params('uid');

        // Check token
        $refresh = Pi::api('token', 'tools')->refresh($token, $uid);
        if ($refresh['status'] == 1) {
            $result = [
                'result' => true,
                'data'   => [
                    $refresh,
                ],
                'error'  => [
                    'code'    => 0,
                    'message' => '',
                ],
            ];
        } else {
            $result['error']['code']    = $refresh['code'];
            $result['error']['message'] = $refresh['message'];
        }

        // Return result
        return $result;
    }

    public function deviceTokenAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get device token
            $deviceToken = $this->params('device_token');

            // Update device token
            if (!empty($deviceToken)) {
                Pi::api('user', 'tools')->updateDeviceToken($check['uid'], $deviceToken);

                $result = [
                    'result' => true,
                    'data'   => [
                        [
                            'message' => __('Device token updated'),
                        ],
                    ],
                    'error'  => [
                        'code'    => 0,
                        'message' => '',
                    ],
                ];
            } else {
                $result['error'] = [
                    'code'    => 1,
                    'message' => __('Device token is empty !'),
                ];
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        // Return result
        return $result;
    }

    public function loginAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Check post array set or not
        if (!$this->request->isPost()) {
            $result['error']['message'] = __('Post request not set');
        } else {
            // Get from post
            $post = $this->request->getPost();

            // Check identity and credential
            if (isset($post['identity']) && !empty($post['identity']) && isset($post['credential']) && !empty($post['credential'])) {
                // Do login
                $loginResult = $this->doLogin(
                    _escape($post['identity']),
                    _escape($post['credential'])
                );

                // Check login
                if ($loginResult['status'] == 1) {
                    $result = [
                        'result' => true,
                        'data'   => [
                            $loginResult,
                        ],
                        'error'  => [
                            'code'    => 0,
                            'message' => '',
                        ],
                    ];
                } else {
                    $result['error']['message'] = $loginResult['message'];
                }
            } else {
                $result['error']['message'] = __('Identity or credential not set');
            }
        }

        return $result;
    }

    public function logoutAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Remove token
            Pi::api('token', 'tools')->remove(
                [
                    'token' => $token,
                ]
            );

            // Remove device token
            Pi::api('user', 'tools')->updateDeviceToken($check['uid'], '');

            // Core logout
            Pi::service('user')->destroy();
            Pi::service('event')->trigger('logout', $check['uid']);

            // Set default result
            $result = [
                'result' => true,
                'data'   => [
                    [
                        'message' => __('You logout successfully !'),
                    ],
                ],
                'error'  => [
                    'code'    => 0,
                    'message' => '',
                ],
            ];
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function profileAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get config
            $configTools = Pi::service('registry')->config->read('tools');

            // Set extra fields
            $extraFields = explode(',', $configTools['fields']);

            // Get user information
            $fields = ['id', 'identity', 'name', 'email', 'device_token'];
            $fields = array_unique(array_merge($fields, $extraFields));

            // Find user
            $user = Pi::user()->get($check['uid'], $fields);

            if ($user && !empty($user)) {
                // Set user data
                $data = [
                    'uid'          => $user['id'],
                    'identity'     => $user['identity'],
                    'email'        => $user['email'],
                    'name'         => $user['name'],
                    'device_token' => $user['device_token'],
                    'avatar'       => Pi::service('user')->avatar($user['id'], 'xlarge', false) . '?' . time(),
                ];

                // Set extra fields
                foreach ($extraFields as $extraField) {
                    $data[$extraField] = isset($user[$extraField]) ? $user[$extraField] : '';
                }

                // Set notification count
                if (Pi::service('module')->isActive('message')) {
                    $data['notification_count']      = Pi::api('api', 'message')->getUnread($user['id'], 'notification');
                    $data['notification_count_view'] = _number($data['notification_count']);
                }

                // Set default result
                $result = [
                    'result' => true,
                    'data'   => [
                        $data,
                    ],
                    'error'  => [
                        'code'    => 0,
                        'message' => '',
                    ],
                ];
            } else {
                $result['error']['message'] = __('user not found !');
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function registerAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Check post array set or not
        if (!$this->request->isPost()) {
            $result['error']['message'] = __('Post request not set');
        } else {
            // Get config
            $config      = Pi::user()->config();
            $configUser  = Pi::service('registry')->config->read('user');
            $configTools = Pi::service('registry')->config->read('tools');

            // Get from post
            $post = $this->request->getPost();

            // Set value array
            $values = [];
            foreach ($post as $key => $value) {
                $key   = _escape($key);
                $value = _escape($value);
                if (!empty($value)) {
                    $values[$key] = $value;
                }
            }

            // Check request not empty
            if (empty($values)) {
                $result['error']['message'] = __('Your request is empty');
                return $result;
            }

            // Set email or mobile as identity if not set on register form
            if (!isset($values['identity']) || empty($values['identity'])) {
                if ($configTools['force_mobile']) {
                    $values['identity'] = $values['mobile'];
                } else {
                    $values['identity'] = $values['email'];
                }
            }

            // Check mobile force set on register form
            if (isset($values['mobile']) && !empty($values['mobile'])) {
                // Set validator
                $validator = new UserMobileValidator(
                    [
                        'checkFormat' => true,
                        'checkTaken'  => true,
                    ]
                );

                // Check is valid
                if (!$validator->isValid($values['mobile'])) {
                    $message                    = array_values($validator->getMessages());
                    $result['error']['message'] = array_shift($message);
                    return $result;
                }
            } elseif ($configTools['force_mobile']) {
                $result['error']['message'] = __('Mobile can not be empty !');
                return $result;
            }

            // Check email force set on register form
            if (isset($values['email']) && !empty($values['email'])) {
                // Set validator
                $validator = new UserEmailValidator(
                    [
                        'blacklist'         => false,
                        'check_duplication' => true,
                        'useMxCheck'        => false,
                        'useDeepMxCheck'    => false,
                        'useDomainCheck'    => false,
                    ]
                );

                // Check is valid
                if (!$validator->isValid($values['email'])) {
                    $message                    = array_values($validator->getMessages());
                    $result['error']['message'] = array_shift($message);
                    return $result;
                }
            } elseif (!$configTools['force_mobile']) {
                $result['error']['message'] = __('Email can not be empty !');
                return $result;
            }

            // Check identity force set on register form
            if (isset($values['identity']) && !empty($values['identity'])) {
                // Set validator
                $validator = new UsernameValidator(
                    [
                        'encoding'          => 'UTF-8',
                        'min'               => $config['uname_min'],
                        'max'               => $config['uname_max'],
                        'format'            => $config['uname_format'],
                        'blacklist'         => $config['uname_blacklist'],
                        'check_duplication' => true,
                    ]
                );

                // Check is valid
                if (!$validator->isValid($values['identity'])) {
                    $message                    = array_values($validator->getMessages());
                    $result['error']['message'] = array_shift($message);
                    return $result;
                }
            } else {
                $result['error']['message'] = __('Identity can not be empty !');
                return $result;
            }

            // Set name if not set on register form
            if (!isset($values['name']) || empty($values['name'])) {
                if (isset($values['first_name']) || isset($values['last_name'])) {
                    $values['name'] = $values['first_name'] . ' ' . $values['last_name'];
                } else {
                    $values['name'] = $values['identity'];
                }
            }

            // Check credential
            if (!isset($values['credential']) || empty($values['credential'])) {
                $result['error']['message'] = __('Credential can not be empty !');
                return $result;
            }

            // Set values
            $values['last_modified']   = time();
            $values['ip_register']     = Pi::user()->getIp();
            $values['register_source'] = 'APP';

            // Check mobile is duplicated
            $where = ['identity' => $values['identity']];
            $count = Pi::model('user_account')->count($where);
            if ($count) {
                $result['error']['message'] = __('This identity is taken before by another user');
                return $result;
            }

            // Add user
            $uid = Pi::api('user', 'user')->addUser($values);
            if (!$uid || !is_int($uid)) {
                $result['error']['message'] = __('User account was not saved.');
            } else {
                // Set user role
                Pi::api('user', 'user')->setRole($uid, 'member');

                // Set custom roles
                if (isset($configTools['roles']) && !empty($configTools['roles'])) {
                    $toolsRoles = explode(',', $configTools['roles']);
                    foreach ($toolsRoles as $role) {
                        Pi::api('user', 'user')->setRole($uid, $role);
                    }
                }

                // Active user
                if ($configUser['register_activation'] == 'auto') {
                    $status = Pi::api('user', 'user')->activateUser($uid);
                    if ($status) {

                        // Target activate user event
                        Pi::service('event')->trigger('user_activate', $uid);

                        if (isset($values['email']) && !empty($values['email']) && Pi::user()->config('register_notification')) {
                            $this->sendNotification(
                                'success',
                                [
                                    'email'    => $values['email'],
                                    'uid'      => $uid,
                                    'identity' => $values['identity'],
                                    'name'     => $values['name'],

                                ]
                            );
                        }

                        // Set result
                        $result = [
                            'result' => true,
                            'data'   => [
                                [
                                    'register_activation' => $configUser['register_activation'],
                                    'message'             => __('Your account create and activate. please login to system'),
                                ],
                            ],
                            'error'  => [
                                'code'    => 0,
                                'message' => '',
                            ],
                        ];
                    }
                } elseif ($configUser['register_activation'] == 'email') {

                    $status = $this->sendNotification(
                        'activation',
                        [
                            'email'    => $values['email'],
                            'uid'      => $uid,
                            'identity' => $values['identity'],
                            'name'     => $values['name'],

                        ]
                    );

                    if (!$status) {
                        $result['error']['message'] = __('Account activation email was not able to send, please contact admin.');
                        return $result;
                    } else {
                        // Set result
                        $result = [
                            'result' => true,
                            'data'   => [
                                [
                                    'register_activation' => $configUser['register_activation'],
                                    'message'             => __('An email with activation link has been sent to you.'),
                                ],
                            ],
                            'error'  => [
                                'code'    => 0,
                                'message' => '',
                            ],
                        ];
                    }


                } elseif ($configUser['register_activation'] == 'admin') {
                    if (isset($values['email']) && !empty($values['email']) && Pi::user()->config('register_notification')) {
                        $this->sendNotification(
                            'admin',
                            [
                                'email'    => $values['email'],
                                'uid'      => $uid,
                                'identity' => $values['identity'],
                                'name'     => $values['name'],

                            ]
                        );
                    }

                    // Set result
                    $result = [
                        'result' => true,
                        'data'   => [
                            [
                                'register_activation' => $configUser['register_activation'],
                                'message'             => __(
                                    'You account has been registered successfully. However it needs to be approved by our admins before you can use it.'
                                ),
                            ],
                        ],
                        'error'  => [
                            'code'    => 0,
                            'message' => '',
                        ],
                    ];
                }

                // Send notification email to admin
                /* if (Pi::user()->config('register_notification_admin')) {
                    $this->sendNotificationToAdmin(
                        $configUser['register_activation'],
                        [
                            'email'    => $values['email'],
                            'identity' => $values['identity'],
                            'name'     => $values['name'],
                            'uid'      => $uid,
                        ]
                    );
                } */
            }
        }

        return $result;
    }

    public function editAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get config
            $configUser  = Pi::service('registry')->config->read('user');
            $configTools = Pi::service('registry')->config->read('tools');
            $loginField  = array_shift($configUser['login_field']);

            // Set extra fields
            $extraFields = explode(',', $configTools['fields']);

            // Get user information
            $fields = ['name', 'email'];
            $fields = array_unique(array_merge($fields, $extraFields));

            // Find user
            $user = Pi::user()->get($check['uid'], $fields);

            // Get from post
            $post = $this->request->getPost();

            // Clean params and set value
            $values = [];
            foreach ($post as $key => $value) {
                if (in_array($key, $fields)) {
                    $values[$key] = _escape($value);
                }
            }

            // Check request not empty
            if (empty($values)) {
                $result['error']['message'] = __('Your request is empty');
                return $result;
            }

            switch ($loginField) {
                case 'identity':
                    unset($values['identity']);
                    unset($values['mobile']);
                    break;

                case 'email':
                    unset($values['email']);
                    break;
            }

            // Check mobile force set on register form
            /* if ($loginField != 'identity' && isset($values['mobile']) && !empty($values['mobile'])) {
                // Set validator
                $validator = new UserMobileValidator(
                    [
                        'checkFormat' => true,
                        'checkTaken'  => true,
                    ]
                );

                // Check is valid
                if (!$validator->isValid($values['mobile'])) {
                    $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                    return $result;
                }
            }

            // Check email force set on register form
            if ($loginField != 'email' && isset($values['email']) && !empty($values['email'])) {
                    // Set validator
                    $validator = new UserEmailValidator(
                        [
                            'blacklist'         => false,
                            'check_duplication' => true,
                            'useMxCheck'        => false,
                            'useDeepMxCheck'    => false,
                            'useDomainCheck'    => false,
                        ]
                    );

                    // Check is valid
                    if (!$validator->isValid($values['email'])) {
                        $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                        return $result;
                    }
                }
            } */

            // Set name
            $values['name']          = $values['first_name'] . ' ' . $values['last_name'];
            $values['last_modified'] = time();

            // do update
            $status = Pi::api('user', 'user')->updateUser($check['uid'], $values);


            // Check update
            if ($status == 1) {
                Pi::service('event')->trigger('user_update', $check['uid']);
                $result = [
                    'result' => true,
                    'data'   => [
                        [
                            'message' => __('User data update successful.'),
                        ],
                    ],
                    'error'  => [
                        'code'    => 0,
                        'message' => '',
                    ],
                ];
            } else {
                $result['error']['message'] = __('Error to update user data !');
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function avatarAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get user information
            $fields = ['id', 'identity', 'name', 'email', 'avatar'];

            // Find user
            $user = Pi::user()->get($check['uid'], $fields);

            if ($user && !empty($user)) {

                // Get post
                $post = $this->request->getPost();
                $post = $post->toArray();

                // Get file
                $file = $this->request->getFiles();

                // upload video
                if (isset($file['avatar']['name']) && !empty($file['avatar']['name'])) {

                    // Get config
                    $configUser = Pi::service('registry')->config->read('user');

                    // Set destination
                    $destination = $configUser['path_tmp'] ?: 'upload/user/tmp';

                    // Upload file
                    $uploader = new Upload();
                    $uploader->setDestination($destination);
                    $uploader->setRename('%random%');
                    $uploader->setExtension('jpg,jpeg,png');
                    $uploader->setSize(10000000);
                    if ($uploader->isValid()) {
                        $uploader->receive();

                        // Set info
                        $avatarUploaded = $uploader->getUploaded('avatar');

                        // Set image full path
                        $rawImage = Pi::path(sprintf('%s/%s', $destination, $avatarUploaded));

                        // Resolve allowed image extension
                        $imageSize      = [];
                        $imageSizeRaw   = getimagesize($rawImage);
                        $imageSize['w'] = $imageSizeRaw[0];
                        $imageSize['h'] = $imageSizeRaw[1];

                        // Set avatar
                        $adapter = Pi::avatar()->getAdapter('upload');
                        $paths   = $adapter->getMeta($check['uid']);
                        foreach ($paths as $path) {
                            Pi::image()->crop(
                                $rawImage,
                                [0, 0],
                                [$imageSize['w'], $imageSize['h']],
                                $path['path']
                            );
                            Pi::image()->resize(
                                $path['path'],
                                [$path['size'], $path['size']]
                            );
                        }

                        // Set avatar file name
                        $meta  = array_pop($paths);
                        $photo = basename($meta['path']);

                        // Save avatar data into database
                        Pi::user()->set($check['uid'], 'avatar', $photo);

                        // Set default result
                        $result = [
                            'result' => true,
                            'data'   => [
                                [
                                    'url' => Pi::service('user')->avatar($check['uid'], 'xlarge', false) . '?' . time(),
                                ],
                            ],
                            'error'  => [
                                'code'    => 0,
                                'message' => '',
                            ],
                        ];
                    } else {
                        // Set error
                        $result['error'] = [
                            'message' => __('Error to upload video !'),
                            'post'    => $post,
                            'file'    => $file,
                        ];
                    }
                } else {
                    // Set error
                    $result['error'] = [
                        'message' => __('File not set !'),
                        'post'    => $post,
                        'file'    => $file,
                    ];
                }
            } else {
                $result['error']['message'] = __('user not found !');
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function passwordAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Check post array set or not
        if (!$this->request->isPost()) {
            $result['error']['message'] = __('Post request not set');
        } else {
            // Get from post
            $post = $this->request->getPost();
            $email = _escape($post['email']);

            // Check email
            if (isset($email) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

                // Get config
                $configUser  = Pi::service('registry')->config->read('user');

                // Get user
                $userRow = Pi::service('user')->getUser($email, 'email');

                // Check user
                if (!$userRow) {
                    $result['error']['message'] = __('User not found.');
                } else {

                    // Set user data
                    $uid = (int)$userRow->id;

                    $token = $this->createTokenPassword($uid, $userRow->email);
                    Pi::user()->data()->set(
                        $uid,
                        'find-password',
                        $token,
                        'user',
                        $configUser['email_expiration'] * 3600
                    );
                    /* Pi::user()->data()->set(
                        $uid,
                        'redirect-password',
                        $redirect,
                        'user',
                        $configUser['email_expiration'] * 3600
                    ); */

                    // Send verify email
                    $to   = $userRow->email;
                    $url  = $this->url(
                        'user',
                        [
                            'module' => 'user',
                            'controller' => 'password',
                            'action' => 'process',
                            'token'  => $token,
                        ]
                    );
                    $link = Pi::url($url, true);

                    $params = [
                        'username'          => $userRow->identity,
                        'find_password_url' => $link,
                        'expiration'        => $configUser['email_expiration'],
                    ];

                    // Load from HTML template
                    $data = Pi::service('mail')->template(
                        [
                            'file'   => 'find-password-html',
                            'module' => 'user',
                        ],
                        $params
                    );

                    // Mail body logging
                    Pi::user()->data()->set(
                        $uid,
                        'find-password-body',
                        $data['body']
                    );

                    // Set subject and body
                    $subject = $data['subject'];
                    $body    = $data['body'];
                    $type    = $data['format'];

                    $message = Pi::service('mail')->message($subject, $body, $type);
                    $message->addTo($to);
                    Pi::service('mail')->send($message);


                    // Set default result
                    $result = [
                        'result' => true,
                        'data'   => [
                            'message' => __('We sent an email to reset your password. Please check your email and follow the instructions to reset it.')
                        ],
                        'error'  => [],
                    ];
                }
            } else {
                $result['error']['message'] = __('Email address not set or dont have true format !');
            }
        }

        return $result;
    }

    public function doLogin($identity, $credential)
    {
        // Set return array
        $return = [
            'status'       => 0,
            'uid'          => 0,
            'token'        => '',
            'message'      => '',
            'identity'     => '',
            'email'        => '',
            'name'         => '',
            'first_name'   => '',
            'last_name'    => '',
            'device_token' => '',
            'avatar'       => '',
            'roles'        => [],
        ];

        // Set field
        $config      = Pi::service('registry')->config->read('user');
        $configTools = Pi::service('registry')->config->read('tools');

        // Set authentication
        // ToDo : Add authentication strategy for API section to remove session
        Pi::service('authentication')->setStrategy('Local');
        $result = Pi::service('authentication')->authenticate(
            $identity,
            $credential,
            array_shift($config['login_field'])
        );
        $result = $this->verifyResult($result);

        // Check login is valid
        if ($result->isValid()) {
            $uid = (int)$result->getData('id');

            // Bind user information
            if (Pi::service('user')->bind($uid)) {

                // Set extra fields
                $extraFields = explode(',', $configTools['fields']);

                // Get user information
                $fields = ['id', 'identity', 'name', 'email', 'first_name', 'last_name', 'device_token'];
                $fields = array_unique(array_merge($fields, $extraFields));

                // Find user
                $user = Pi::user()->get($uid, $fields);

                // Set return array
                $return['message']      = __('You have logged in successfully');
                $return['status']       = 1;
                $return['uid']          = $user['id'];
                $return['identity']     = $user['identity'];
                $return['email']        = $user['email'];
                $return['name']         = $user['name'];
                $return['first_name']   = $user['first_name'];
                $return['last_name']    = $user['last_name'];
                $return['device_token'] = $user['device_token'];

                // Set extra fields
                foreach ($extraFields as $extraField) {
                    $return[$extraField] = isset($user[$extraField]) ? $user[$extraField] : '';
                }

                // Set notification count
                if (Pi::service('module')->isActive('message')) {
                    $return['notification_count']      = Pi::api('api', 'message')->getUnread($user['id'], 'notification');
                    $return['notification_count_view'] = _number($return['notification_count']);
                }

                // Get avatar
                $return['avatar'] = Pi::service('user')->avatar($user['id'], 'xlarge', false) . '?' . time();

                // Get user roles
                $return['roles'] = Pi::service('user')->getRole($user['id'], 'front');

                // Set token
                $return['token'] = Pi::api('token', 'tools')->add($uid);

                // Set user login event
                $params = ['uid' => $uid];
                Pi::service('event')->trigger('user_login', $params);
            } else {
                $return['message'] = __('Bind error');
            }
        } else {
            $return['message'] = __('Authentication is not valid');
        }

        return $return;
    }

    protected function verifyResult(Result $result)
    {
        return $result;
    }

    protected function sendNotification($type, array $data)
    {
        $params   = [];
        $template = '';
        switch ($type) {

            case 'success':
                $template = 'register-success-html';
                $redirect = Pi::user()->data()->get($data['uid'], 'register_redirect');
                $url      = Pi::url(Pi::service('authentication')->getUrl('login', $redirect), true);
                $params   = [
                    'username'  => $data['name'],
                    'login_url' => $url,
                ];
                break;

            case 'admin':
                $template = 'register-success-html';
                $params   = [
                    'username' => $data['name'],
                ];
                break;

            case 'activation':
                $token = $this->createToken($data);
                if ($token) {
                    $template = 'register-activation-html';
                    Pi::user()->data()->set(
                        $data['uid'],
                        'register_activation',
                        $token,
                        'user',
                        $this->config('activation_expiration') * 3600
                    );
                    $url    = Pi::url(
                        $this->url(
                            'user',
                            [
                                'module'     => 'user',
                                'controller' => 'register',
                                'action'     => 'activate',
                                'uid'        => md5($data['uid']),
                                'token'      => $token,
                            ]
                        ),
                        true
                    );
                    $params = [
                        'username'       => $data['name'],
                        'activation_url' => $url,
                    ];
                }
                break;

            default:
                break;
        }
        if (!$template) {
            return false;
        }

        // Load from HTML template
        $template = Pi::service('mail')->template(
            [
                'file'   => $template,
                'module' => 'user',
            ]
            , $params
        );
        $subject  = $template['subject'];
        $body     = $template['body'];
        $typeMail = $template['format'];

        // Send email
        $message = Pi::service('mail')->message($subject, $body, $typeMail);
        $message->addTo($data['email']);
        $result = Pi::service('mail')->send($message);

        // Module message : Notification
        if (Pi::service('module')->isActive('message')) {
            if ($type == 'success' || $type == 'admin') {
                $template = Pi::service('mail')->template(
                    [
                        'file'   => 'notify-register-success-html',
                        'module' => 'user',
                    ], $params
                );
                Pi::api('api', 'message')->notify($data['uid'], $template['body'], $template['subject']);
            }
        }

        return $result;
    }

    protected function sendNotificationToAdmin($type, array $data)
    {
        $params   = [];
        $template = '';
        switch ($type) {
            case 'auto':
                $template = 'admin-notification-register-auto';
                break;

            case 'email':
                $template = 'admin-notification-register-email';
                break;

            case 'admin':
                $template = 'admin-notification-register-approval';
                break;

            default:
                break;
        }

        $params = [
            'identity' => $data['identity'],
            'email'    => $data['email'],
            'name'     => $data['name'],
        ];

        // Set admin mail
        $adminmail = Pi::config('adminmail');
        $adminname = Pi::config('adminname');
        $toAdmin   = [
            $adminmail => $adminname,
        ];

        // Load from HTML template
        $template = Pi::service('mail')->template(
            [
                'file'   => $template,
                'module' => 'user',
            ],
            $params
        );
        $subject  = $template['subject'];
        $body     = $template['body'];
        $type     = $template['format'];

        // Send email
        $message = Pi::service('mail')->message($subject, $body, $type);
        $message->addTo($toAdmin);
        $result = Pi::service('mail')->send($message);

        return $result;
    }

    protected function createToken(array $data)
    {
        $token = '';
        if (!empty($data['uid']) && !empty($data['identity'])) {
            $token = md5($data['uid'] . $data['identity']);
        }

        return $token;
    }

    protected function createTokenPassword($uid, $email)
    {
        $token = md5($uid . $email . Pi::config('salt') . mt_rand());

        return $token;
    }
}
