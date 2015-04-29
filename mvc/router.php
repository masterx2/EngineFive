<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 4/29/15
 * Time: 11:20 PM
 */

namespace App;

use Silex\Application,
    App\Controllers\Auth,
    Symfony\Component\HttpFoundation\Request;

$app = new Application();
$app['debug'] = true;

$app->before(function (Request $request, Application $app) {
    $auth = new Auth();
    $app['auth'] = $auth->getAuth();
});

$app->get('/', 'App\Controllers\Content\Home::index');
$app->get('/api', 'App\Controllers\Content\Api::index');

$app->run();