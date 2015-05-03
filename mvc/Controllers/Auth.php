<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/23/15
 * Time: 8:27 PM
 */

namespace App\Controllers;

use App\Models\Account;
use App\Models\Visitor;

class Auth {
    public function getAuth() {
        $visitorModel = new Visitor();
        if (isset($_COOKIE['sid'])) {
            $sid = $visitorModel->updateSession($_COOKIE['sid']);
            $accountModel = new Account();
            $accounts = $accountModel->getAccounts($sid);
            if ($accounts) {
                if (count($accounts) == 1) {
                    if (!$accounts['active']) {
                        $accountModel->activateAccount(['visitor'=>$sid], $accounts[0]['_id']);
                    }
                } else if (count($accounts) > 1) {

                }
                $auth_result = [
                    'visitor' => $sid,
                    'accounts' => $accounts
                ];
            } else {
                $auth_result = [
                    'visitor' => $sid
                ];
            }
        } else {
            $sid = $visitorModel->createVisitor();
            $auth_result = [
                'visitor' => $sid
            ];
        }
        return $auth_result;
    }
}