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
                    'check'    => 1,
                    'uid'      => $user['id'],
                    'identity' => $user['identity'],
                    'email'    => $user['email'],
                    'name'     => $user['name'],
                    'avatar'   => Pi::service('user')->avatar($user['id'], 'large', false),
                ],
                'error'  => [],
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
                'data'   => $refresh,
                'error'  => [],
            ];
        } else {
            $result['error']['code']    = $refresh['code'];
            $result['error']['message'] = $refresh['message'];
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
                        'data'   => $loginResult,
                        'error'  => [],
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
                'data'   => [],
                'error'  => [],
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

            // Get user information
            $fields = [
                'id', 'identity', 'name', 'email', 'first_name', 'last_name', 'id_number', 'phone', 'mobile', 'address1', 'address2',
                'country', 'state', 'city', 'zip_code', 'company', 'device_token', 'latitude', 'longitude',
            ];

            // Find user
            $user = Pi::user()->get($check['uid'], $fields);

            if ($user && !empty($user)) {
                // Set default result
                $result = [
                    'result' => true,
                    'data'   => [
                        'uid'         => $user['id'],
                        'identity'    => $user['identity'],
                        'email'       => $user['email'],
                        'name'        => $user['name'],
                        'first_name'  => isset($user['first_name']) ? $user['first_name'] : '',
                        'last_name'   => isset($user['last_name']) ? $user['last_name'] : '',
                        'id_number'   => isset($user['id_number']) ? $user['id_number'] : '',
                        'phone'       => isset($user['phone']) ? $user['phone'] : '',
                        'mobile'      => isset($user['mobile']) ? $user['mobile'] : '',
                        'address1'    => isset($user['address1']) ? $user['address1'] : '',
                        'address2'    => isset($user['address2']) ? $user['address2'] : '',
                        'country'     => isset($user['country']) ? $user['country'] : '',
                        'state'       => isset($user['state']) ? $user['state'] : '',
                        'city'        => isset($user['city']) ? $user['city'] : '',
                        'zip_code'    => isset($user['zip_code']) ? $user['zip_code'] : '',
                        'company'     => isset($user['company']) ? $user['company'] : '',
                        'company_id'  => isset($user['company_id']) ? $user['company_id'] : '',
                        'company_vat' => isset($user['company_vat']) ? $user['company_vat'] : '',
                        'latitude'    => isset($user['latitude']) ? $user['latitude'] : '',
                        'longitude'   => isset($user['longitude']) ? $user['longitude'] : '',
                        'avatar'      => Pi::service('user')->avatar($user['id'], 'large', false),
                    ],
                    'error'  => [],
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
            $config = Pi::user()->config();
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

            // Set email as identity if not set on register form
            if (!isset($values['identity']) || empty($values['identity'])) {
                $values['identity'] = $values['mobile'];
            }

            // Check mobile force set on register form
            if (isset($values['mobile']) && empty($values['mobile'])) {
                // Set validator
                $validator = new UserMobileValidator(
                    [
                        'checkFormat' => true,
                        'checkTaken'  => true,
                    ]
                );

                // Check is valid
                if (!$validator->isValid($values['mobile'])) {
                    $result['error'] = $validator->getMessages();
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
                    $result['error'] = $validator->getMessages();
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
                    $result['error'] = $validator->getMessages();
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
                                'register_activation' => $configUser['register_activation'],
                                'message' => __('Your account create and activate. please login to system'),
                            ],
                            'error'  => [],
                        ];
                    }
                } elseif ($configUser['register_activation'] == 'email') {
                    // Set result
                    $result = [
                        'result' => true,
                        'data'   => [
                            'register_activation' => $configUser['register_activation'],
                            'message' => __('An email with activation link has been sent to you.'),
                        ],
                        'error'  => [],
                    ];
                } elseif ($configUser['register_activation'] == 'approval') {
                    // Set result
                    $result = [
                        'result' => true,
                        'data'   => [
                            'register_activation' => $configUser['register_activation'],
                            'message' => __('You account has been registered successfully. However it needs to be approved by our admins before you can use it.'),
                        ],
                        'error'  => [],
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

            // ToDo : finish it

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
            'check'        => 0,
            'uid'          => 0,
            'token'        => '',
            'message'      => '',
            'identity'     => '',
            'email'        => '',
            'name'         => '',
            'first_name'   => '',
            'last_name'    => '',
            'id_number'    => '',
            'phone'        => '',
            'mobile'       => '',
            'address1'     => '',
            'address2'     => '',
            'country'      => '',
            'state'        => '',
            'city'         => '',
            'zip_code'     => '',
            'company'      => '',
            'device_token' => '',
            'latitude'     => '',
            'longitude'    => '',
            'avatar'       => '',
        ];

        // Set field
        $config = Pi::service('registry')->config->read('user');

        // try login
        $result = Pi::service('authentication')->authenticate(
            $identity,
            $credential,
            $config['login_field']
        );
        $result = $this->verifyResult($result);

        // Check login is valid
        if ($result->isValid()) {
            $uid = (int)$result->getData('id');

            // Bind user information
            if (Pi::service('user')->bind($uid)) {

                // Get user information
                $fields = [
                    'id', 'identity', 'name', 'email', 'first_name', 'last_name', 'id_number', 'phone', 'mobile', 'address1', 'address2',
                    'country', 'state', 'city', 'zip_code', 'company', 'device_token', 'latitude', 'longitude',
                ];

                // Find user
                $user = Pi::user()->get($uid, $fields);

                // Set return array
                $return['message']     = __('You have logged in successfully');
                $return['status']      = 1;
                $return['check']       = 1;
                $return['uid']         = $user['id'];
                $return['identity']    = $user['identity'];
                $return['email']       = $user['email'];
                $return['name']        = $user['name'];
                $return['first_name']  = isset($user['first_name']) ? $user['first_name'] : '';
                $return['last_name']   = isset($user['last_name']) ? $user['last_name'] : '';
                $return['id_number']   = isset($user['id_number']) ? $user['id_number'] : '';
                $return['phone']       = isset($user['phone']) ? $user['phone'] : '';
                $return['mobile']      = isset($user['mobile']) ? $user['mobile'] : '';
                $return['address1']    = isset($user['address1']) ? $user['address1'] : '';
                $return['address2']    = isset($user['address2']) ? $user['address2'] : '';
                $return['country']     = isset($user['country']) ? $user['country'] : '';
                $return['state']       = isset($user['state']) ? $user['state'] : '';
                $return['city']        = isset($user['city']) ? $user['city'] : '';
                $return['zip_code']    = isset($user['zip_code']) ? $user['zip_code'] : '';
                $return['company']     = isset($user['company']) ? $user['company'] : '';
                $return['company_id']  = isset($user['company_id']) ? $user['company_id'] : '';
                $return['company_vat'] = isset($user['company_vat']) ? $user['company_vat'] : '';
                $return['latitude']    = isset($user['latitude']) ? $user['latitude'] : '';
                $return['longitude']   = isset($user['longitude']) ? $user['longitude'] : '';

                // Get avatar
                $return['avatar'] = Pi::service('user')->avatar($user['id'], 'large', false);

                // Set token
                $return['token'] = Pi::api('token', 'tools')->add($uid);

                // Set user login event
                $params = [
                    'uid' => $uid,
                ];
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