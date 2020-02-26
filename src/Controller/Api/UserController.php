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
            $fields = ['id', 'identity', 'name', 'email'];

            // Get user
            $user = Pi::user()->get($check['uid'], $fields);

            // Set default result
            $result = [
                'result' => true,
                'data'   => [
                    [
                        'check'    => 1,
                        'uid'      => $user['id'],
                        'identity' => $user['identity'],
                        'email'    => $user['email'],
                        'name'     => $user['name'],
                        'avatar'   => Pi::service('user')->avatar($user['id'], 'large', false),
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
                    'avatar'       => Pi::service('user')->avatar($user['id'], 'large', false),
                ];

                // Set extra fields
                foreach ($extraFields as $extraField) {
                    $data[$extraField] = isset($user[$extraField]) ? $user[$extraField] : '';
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
                $result['error']['message'] = __('user not fined !');
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
            $config     = Pi::user()->config();
            $configUser = Pi::service('registry')->config->read('user');

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

            // Set email as identity if not set on register form
            if (!isset($values['identity']) || empty($values['identity'])) {
                $values['identity'] = $values['mobile'];
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
                    $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                    return $result;
                }
            } else {
                $result['error'] = __('Mobile can not be empty !');
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
                    $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                    return $result;
                }
            } else {
                $result['error'] = __('Email can not be empty !');
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
                    $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                    return $result;
                }
            } else {
                $result['error'] = __('Identity can not be empty !');
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

            // Set values
            $values['last_modified'] = time();
            $values['ip_register']   = Pi::user()->getIp();

            // Check mobile is duplicated
            $where = ['identity' => $values['identity']];
            $count = Pi::model('user_account')->count($where);
            if ($count) {
                $result['error'] = __('This identity is taken before by another user');
                return $result;
            }

            // Add user
            $uid = Pi::api('user', 'user')->addUser($values);
            if (!$uid || !is_int($uid)) {
                $result['error'] = __('User account was not saved.');
            } else {
                // Set user role
                Pi::api('user', 'user')->setRole($uid, 'member');

                // Active user
                if ($configUser['register_activation'] == 'auto') {
                    $status = Pi::api('user', 'user')->activateUser($uid);
                    if ($status) {

                        // Target activate user event
                        Pi::service('event')->trigger('user_activate', $uid);

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
                } elseif ($configUser['register_activation'] == 'approval') {
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

            // Check mobile force set on register form
            if ($loginField != 'identity') {
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
                        $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                        return $result;
                    }
                }
            } else {
                unset($values['mobile']);
            }

            // Check email force set on register form
            if ($loginField != 'email') {
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
                        $result['error']['message'] = array_shift(array_values($validator->getMessages()));
                        return $result;
                    }
                }
            } else {
                unset($values['email']);
            }

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
            'device_token' => '',
            'avatar'       => '',
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
                $fields = ['id', 'identity', 'name', 'email', 'device_token'];
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
                $return['device_token'] = $user['device_token'];

                // Set extra fields
                foreach ($extraFields as $extraField) {
                    $return[$extraField] = isset($user[$extraField]) ? $user[$extraField] : '';
                }

                // Get avatar
                $return['avatar'] = Pi::service('user')->avatar($user['id'], 'large', false);

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
}
