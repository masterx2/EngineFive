<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 4/29/15
 * Time: 11:20 PM
 */

namespace App;

use App\Models\Account;
use App\Models\Visitor;
use App\Controllers\Content\Home;

class Router {
    
    public static $url;
    public static $visitor;
    public static $account;
    public static $fenom;
    
    public static function start() {

        self::$visitor = new Visitor(isset($_COOKIE['sid']) ? ["sid" => $_COOKIE['sid']] : null);
        self::$account = new Account();
        self::$fenom = \Fenom::factory('../templates', '../var/cache', [
            'force_compile' => true,
            'strip' => true
        ]);

        $prefix = "App\\Controllers\\Content\\";

        if (isset($_GET['url'])) {
            self::$url = $_GET['url'];
            $url_parts = explode('/', self::$url);
            $class_name = strtolower(array_shift($url_parts));
            $call_class = $prefix.ucfirst($class_name);
            if (class_exists($call_class)) {
                $controller = new $call_class();
                if (count($url_parts)) {
                    $method = strtolower(array_shift($url_parts));
                    if (method_exists($controller, $method)) {
                        self::render($class_name.'/'.$method, $controller->$method($url_parts));
                    } else {
                        array_unshift($url_parts, $method);
                        self::render($class_name, $controller->index($url_parts));
                    }
                } else {
                    self::render($class_name, $controller->index());
                }
            } else {
                array_unshift($url_parts, $class_name);
                $controller = new Home();
                $method = array_shift($url_parts);
                if (method_exists($controller, $method)) {
                    self::render('home/'.$method, $controller->$method($url_parts));
                } else {
                    array_unshift($url_parts, $method);
                    self::render('home', $controller->index($url_parts));
                }
            }
        } else {
            $controller = new Home();
            self::render('home', $controller->index());
        }
    }

    public static function render($template, $args=[]) {
        if (empty($args) || !is_array($args)){
            $args = [];
        }
        $work_template = file_exists('../templates/'.$template.'.tpl') ? $template.'.tpl' : 'default.tpl';
        if (isset($_GET['format']) && $_GET['format'] == 'json') {
            header('Content-Type: application/json; Encoding: utf-8');
            echo json_encode($args, JSON_UNESCAPED_UNICODE);
            exit(0);
        } else {
            self::$fenom->display($work_template, $args);
        }
    }
}