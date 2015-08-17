<?php

namespace App\Models\Db;

/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 24.07.15
 * Time: 20:31
 */
class Mongo {
    /**
     * @var \MongoDB
     */
    public static $db;

    /**
     * @param string $config
     */
    public static function connect($config) {
        $mc = new \MongoClient($config['server']);
        self::$db = $mc->$config['dbname'];
    }
}