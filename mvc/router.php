<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 4/29/15
 * Time: 11:20 PM
 */

namespace App;

use App\Controllers\Auth;
use App\Controllers\Content\Home;

class Router {
    public function __construct() {
        $auth = new Auth();
        $this->auth = $auth->getAuth();

        $prefix = "App\\Controllers\\Content\\";

        if (isset($_GET['url'])) {
            $this->url = $_GET['url'];
            $url_parts = explode('/', $this->url);
            $class_name = array_shift($url_parts);
            $call_class = $prefix.ucfirst($class_name);
            if (class_exists($call_class)) {
                $process = new $call_class($this->auth);
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
                $process = new Home($this->auth);
                $method = array_shift($url_parts);
                if (method_exists($process, $method)) {
                    $process->$method($url_parts);
                } else {
                    array_unshift($url_parts, $method);
                    $process->index($url_parts);
                }
            }
        } else {
            $process = new Home($this->auth);
            $process->index();
        }
    }
}