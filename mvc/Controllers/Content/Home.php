<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 4/29/15
 * Time: 11:23 PM
 */

namespace App\Controllers\Content;

use App\Views\Main;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Home {
    public function index(Request $request, Application $app) {
        $view = new Main();
        return $view->render('index.tpl',[]);
    }
}