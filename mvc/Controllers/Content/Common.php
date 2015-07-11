<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 5/2/15
 * Time: 2:50 PM
 */

namespace App\Controllers\Content;

use App\Views\Main;

abstract class Common {
    function __construct() {
        $this->content = new Main();
    }
}