<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 4/29/15
 * Time: 11:23 PM
 */

namespace App\Controllers\Content;

use App\Models\Account;

class Home extends Common {
    public function index() {
        return 'sdfdfsdf';
    }

    public function wrongway() {
        echo 'WRONG WAY!!';
    }

    public function hello() {
        echo 'hello page';
    }

    public function video() {
        return 'Some text';
    }

    public function login() {
        $form = $this->content->getForm(Account::$schema, 'login');
        $this->content->display('login.tpl',['form' => $form]);
    }

    public function logout() {
        // pass
    }

    public function register() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return [
                    'form' => $this->content->getForm(Account::$schema, 'register')
                ];
                break;
            case 'POST':
                $account = new Account();
                $result = $account->registerAccount($this->auth);
                break;
        }
    }
}