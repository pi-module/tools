<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt BSD 3-Clause License
 */

namespace Module\Tools\Controller\Front;

use Pi;
use Pi\Authentication\Result;
use Pi\Mvc\Controller\ActionController;

/**
 * OAuth controller
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class OauthController extends ActionController
{
    public function indexAction()
    {
        // Check user
        if (Pi::service('user')->hasIdentity()) {
            $this->jump(array('route' => 'home'), __('You logged in before.'));
        }

        $redirectUri = Pi::url($this->url('', array(
            'module'     => 'tools',
            'controller' => 'oauth',
            'action'     => 'callback',
        )));

        $googleAccountUrl = 'https://accounts.google.com/o/oauth2/v2/auth?scope=%s&response_type=code&redirect_uri=%s&client_id=%s';
        $googleScope = 'https://www.googleapis.com/auth/userinfo.email';

        $loginUrl = sprintf(
            $googleAccountUrl,
            $googleScope,
            $redirectUri,
            $this->config('oauth_google_client_id')
        );

        // Set template
        $this->view()->setTemplate('oauth-index');
        $this->view()->assign('loginUrl', $loginUrl);
        $this->view()->assign('uid', $uid);
    }

    public function callbackAction()
    {
        $redirectUri = Pi::url($this->url('', array(
            'module'     => 'tools',
            'controller' => 'oauth',
            'action'     => 'callback',
        )));

        $googleApiUrl = 'https://www.googleapis.com/plus/v1/people/me?fields=aboutMe%2Cemails%2Cimage%2Cname&access_token=';
        $googleUrl = "https://www.googleapis.com/oauth2/v4/token";
        
        if (empty($_GET['redirect'])) {
            $redirect = array('route' => 'home');
        } else {
            $redirect = urldecode($_GET['redirect']);
        }

        // Check user
        if (Pi::service('user')->hasIdentity()) {
            $this->jump($redirect, __('You logged in before.'));
        }

        $header = array("Content-Type: application/x-www-form-urlencoded");
        $data = http_build_query(
            array(
                'code'          => str_replace("#", null, $_GET['code']),
                'client_id'     => $this->config('oauth_google_client_id'),
                'client_secret' => $this->config('oauth_google_client_secret'),
                'redirect_uri'  => $redirectUri,
                'grant_type'    => 'authorization_code'
            )
        );
        
        $result = $this->googleRequest(1, $googleUrl, $header, $data);

        if (!empty($result['error'])) {
            $this->jump($redirect, __('Error on login'));
        } else {
            $googleApiUrl = $googleApiUrl . $result['access_token'];
            $userInfo     = $this->googleRequest(0, $googleApiUrl, 0, 0);
            $email        = $userInfo['emails'][0]['value'];

            // Check user
            $userAccount  = Pi::model('user_account')->find($email, 'email');
            if (!$userAccount) {
                // Add user
                $user                   = array();
                $user['first_name']     = $userInfo['name']['givenName'];
                $user['last_name']      = $userInfo['name']['familyName'];
                $user['email']          = $userInfo['emails'][0]['value'];
                $user['identity']       = $userInfo['emails'][0]['value'];
                $user['name']           = sprintf('%s %s', $userInfo['name']['givenName'], $userInfo['name']['familyName']);
                $user['last_modified']  = time();
                $user['ip_register']    = Pi::user()->getIp();
                $uid = Pi::api('user', 'user')->addUser($user);

                // Check user add or not
                if ($uid) {
                    // Set user role
                    Pi::api('user', 'user')->setRole($uid, 'member');
                    // Active user
                    $status = Pi::api('user', 'user')->activateUser($uid);
                    if ($status) {
                        // Target activate user event
                        Pi::service('event')->trigger('user_activate', $uid);
                    }
                }
            }

            // Set authentication
            Pi::service('authentication')->setStrategy('oAuth');
            $result = Pi::service('authentication')->authenticate($email, '', '');
            $result = $this->verifyResult($result);

            if (!$result->isValid()) {
                $this->jump($redirect, __('Error on login'));
            } else {
                $configs = Pi::user()->config();

                $uid = (int)$result->getData('id');
                try {
                    Pi::service('user')->bind($uid);
                } catch (\Exception $e) {

                    return;
                }

                Pi::service('session')->setUser($uid);

                $rememberMe = 0;
                if ($configs['rememberme']) {
                    $rememberMe = $configs['rememberme'] * 86400;
                    Pi::service('session')->manager()
                        ->rememberme($rememberMe);
                }

                if (isset($_SESSION['PI_LOGIN'])) {
                    unset($_SESSION['PI_LOGIN']);
                }

                // Trigger login event
                $args = array(
                    'uid' => $uid,
                    'remember_time' => $rememberMe,
                );
                Pi::service('event')->trigger('user_login', $args);

                $this->jump($redirect, __('You have logged in successfully.'));
            }
        }
    }

    public function googleRequest($method, $url, $header, $data)
    {
        if ($method == 1) {
            $method_type = 1; // 1 = POST
        } else {
            $method_type = 0; // 0 = GET
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        if ($header !== 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($curl, CURLOPT_POST, $method_type);

        if ($data !== 0) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);
        $json = json_decode($response, true);
        curl_close($curl);

        return $json;
    }


    /**
     * Filtering Result after authentication
     *
     * @param Result $result
     *
     * @return Result
     */
    protected function verifyResult(Result $result)
    {
        return $result;
    }
}