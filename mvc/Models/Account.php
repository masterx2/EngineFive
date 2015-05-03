<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/23/15
 * Time: 8:30 PM
 */

namespace App\Models;

use App\Utils\Apitools;

class Account extends Common {
    public static $schema = [
        'active' => [
            'default' => true,
            'value_type' => 'bool',
            'control_type' => 'checkbox',
            'scenario' => ['edit']
        ],
        'login' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'input',
            'scenario' => ['register', 'login'],
            'label' => 'Login'
        ],
        'email' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'input',
            'scenario' => ['register', 'login'],
            'label' => 'Email'
        ],
        'name' => [
            'default' => 'Someone',
            'value_type' => 'string',
            'control_type' => 'input',
            'scenario' => ['register', 'edit'],
            'label' => 'Real name'
        ],
        'password' => [
            'default' => 'admin',
            'value_type' => 'string',
            'control_type' => 'input',
            'scenario' => ['register', 'login'],
            'label' => 'Password here'
        ],
        'sessions' => [
            'default' => [],
            'value_type' => 'array',
            'control_type' => 'select',
            'scenario' => ['edit']
        ]
    ];

    public function getAccounts($sid) {
        return $this->query(['sessions' =>  $sid]);
    }

    public function getAllAccounts() {
        return $this->getAll();
    }

    public function registerAccount($auth) {
        $post_data = $this->checkSchema($_POST);
        $account = [
            'active' => true,
            'login' => $post_data['login'],
            'email' => $post_data['email'],
            'name' => $post_data['name'],
            'password' => sha1($post_data['password']),
            'sessions' => [$auth['visitor']]
        ];
        $result = $this->addNext($account);
        return [$account, $result];
//        if (!$result['err']) {
//            $this->activateAccount($auth, $account['_id']);
//        }
    }

    public function loginAccount($auth, $login, $password) {
        $valid_account = $this->findOne(['login' => $login]);
        if ($valid_account) {
            if (sha1($password) == $valid_account['password']) {
                $this->updateByMongoId(
                    $valid_account['_id'],
                    ['$push' => ["sessions" => $auth['visitor']]]
                );
                $this->activateAccount($auth, $valid_account['_id']);
            } else {
                Apitools::error(2001, [
                    'account' => $login,
                    'badpassword' => $password,
                    'visitor' => $auth['visitor']
                ]);
            }
        } else {
            Apitools::error(2002, [
                'badaccount' => $login,
                'visitor' => $auth['visitor']
            ]);
        }
    }

    public function logoutAccount($auth) {
        $acc_cursor = $this->query(['sessions' => $auth['visitor']]);
        foreach ($acc_cursor as $acc) {
            if ($acc['active']) {
                $result = $this->updateByMongoId(
                    $acc['_id'],
                    [
                        '$pull' => ["sessions" => $auth['visitor']],
                        '$set' => ["active" => false]
                    ]
                );
                Apitools::sendJson(['error' => 0, 'result' => $result]);
            }
        }
    }

    public function activateAccount($auth, $account_id) {
        $acc_cursor = $this->query(['sessions' => $auth['visitor']]);
        foreach ($acc_cursor as $account) {
            if ($account['_id'] == $account_id) {
                $this->updateByMongoId(
                    $account['_id'],
                    ['$set' => ['active' => true]]
                );
            } else {
                $this->updateByMongoId(
                    $account['_id'],
                    ['$set' => ['active' => false]]
                );
            }
        }

        Apitools::sendJson([
            'error' => 0,
            'result' => ['active_account' => $account_id],
            'message' => 'API OK'
        ]);
    }
}