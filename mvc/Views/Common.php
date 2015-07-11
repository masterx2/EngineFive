<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/16/15
 * Time: 8:31 PM
 */

namespace App\Views;

use Fenom;

class Common {
    function __construct() {
        $this->fenom = Fenom::factory('../templates', '../var/cache', [
            'force_compile' => true,
            'strip' => true
        ]);
    }

    public function getScript($script) {
        return '<script src="/js/'.$script.'"></script>';
    }

    public function display($tpl, $params) {
        return $this->fenom->display($tpl, $params);
    }
}