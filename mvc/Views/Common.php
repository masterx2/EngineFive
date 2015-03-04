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
        $this->fenom = Fenom::factory('../mvc/templates', '../mvc/cache', [
            'force_compile' => true,
            'strip' => true
        ]);
    }
}