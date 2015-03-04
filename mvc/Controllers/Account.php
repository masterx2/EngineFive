<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/23/15
 * Time: 8:30 PM
 */

namespace App\Controllers;

use App\Models\Db\Mongo,
    App\Utils\Apitools;

class Account extends Mongo {
    public function getAccounts($sid) {
        $accounts_cursor = $this->container->find([
            'sessions' =>  $sid
        ]);
        return iterator_to_array($accounts_cursor);
    }

    public function getAllAccounts() {
        $accounts_cursor = $this->container->find();
        return iterator_to_array($accounts_cursor);
    }

    public function registerAccount($auth, $login, $password, $name) {
        $account = [
            'active' => true,
            'login' => $login,
            'name' => $name,
            'password' => sha1($password),
            'sessions' => [
                $auth['visitor']
            ]
        ];

        $result = $this->container->insert($account);

        if (!$result['err']) {
            $this->activateAccount($auth, $account['_id']);
        }
    }

    public function loginAccount($auth, $login, $password) {
        $valid_account = $this->container->findOne(['login' => $login]);
        if ($valid_account) {
            if (sha1($password) == $valid_account['password']) {
                $this->container->update(
                    [
                        '_id' => new \MongoId($valid_account['_id'])],
                    [
                        '$push' => ["sessions" => $auth['visitor']],
                    ]
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
        $acc_cursor = $this->container->find(['sessions' => $auth['visitor']]);

        foreach ($acc_cursor as $acc) {
            if ($acc['active']) {
                $result = $this->container->update(
                    [
                        '_id' => new \MongoId($acc['_id'])],
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
        $acc_cursor = $this->container->find(['sessions' => $auth['visitor']]);

        foreach ($acc_cursor as $account) {
            if ($account['_id'] == $account_id) {
                $this->container->update(
                    ['_id' => new \MongoId($account['_id'])],
                    ['$set' => ['active' => true]]
                );
            } else {
                $this->container->update(
                    ['_id' => new \MongoId($account['_id'])],
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