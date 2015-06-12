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
    
    public static function start() {

        self::$visitor = new Visitor(isset($_COOKIE['sid']) ? ["sid" => $_COOKIE['sid']] : null);
        self::$account = new Account();

        $prefix = "App\\Controllers\\Content\\";

        if (isset($_GET['url'])) {
            self:$url = $_GET['url'];
            $url_parts = explode('/', self::$url);
            $class_name = array_shift($url_parts);
            $call_class = $prefix.ucfirst($class_name);
            if (class_exists($call_class)) {
                $process = new $call_class();
                if (count($url_parts)) {
                    $method = array_shift($url_parts);
                    if (method_exists($process, $method)) {
                        if (count($url_parts)) {
                            $process->$method($url_parts);
                        } else {
                            $process->$method();
                        }
                    } else {
                        array_unshift($url_parts, $method);
                        $process->index($url_parts);
                    }
                } else {
                    $process->index();
                }
            } else {
                array_unshift($url_parts, $class_name);
                $process = new Home(); // Create
                $method = array_shift($url_parts);
                if (method_exists($process, $method)) {
                    $process->$method($url_parts);
                } else {
                    array_unshift($url_parts, $method);
                    $process->index($url_parts);
                }
            }
        } else {
            $process = new Home(); // Create
            $process->index();
        }
    }
}