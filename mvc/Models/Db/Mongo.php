<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/8/15
 * Time: 11:24 PM
 */

namespace App\Models\Db;

class Mongo {

    private $client;
    public $db;

    function __construct() {
        $reflect_class = new \ReflectionClass(get_class($this));
        $this->client = new \MongoClient();
        $this->db = $this->client->selectDB('masterhome'); # TODO Move Db name to Config
        $this->counters = $this->db->counters;
        $this->container = $this->db->selectCollection(
            strtolower($reflect_class->getShortName())
        );
    }

    public static function clearMongo($data) {
        if(is_array($data)) {
            foreach($data as &$attr) {
                if($attr instanceof \MongoId) {
                    $attr = (string) $attr;
                }
                if($attr instanceof \MongoDate) {
                    $attr = $attr->sec;
                }
                if(is_array($attr)) {
                    $attr = self::clearMongo($attr);
                }
            }
            unset($attr);
        } else {
            return (string) $data;
        }
        return $data;
    }

    public static function checkId($id) {
        if (!($id instanceof \MongoId)) {
            $id = new \MongoId($id);
        }
        return $id;
    }
}