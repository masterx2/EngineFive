<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/8/15
 * Time: 11:24 PM
 */

namespace App\Models\Db;

class Mongo {

    public static $schema;
    private $client;
    public $db;
    public $counters;
    public $container;

    function __construct() {
        $reflect_class = new \ReflectionClass(get_class($this));
        $this->client = new \MongoClient();
        $this->db = $this->client->selectDB('masterhome'); # TODO Move Db name to Config
        $this->counters = $this->db->counters;
        $this->container = $this->db->selectCollection(
            strtolower($reflect_class->getShortName())
        );
    }

    /**
    Schema Field Types

    default: Default value,
    value_type: Db Store type,       <--\ Will be converted
    control_type: From control type, >--/
    label: Field label
    scenario: Used scenario

     */
    public function checkSchema($object) {
        // Fill Object by original schema
        // Convert types from controls to db types
        $new_object = [];
        foreach ($this::$schema as $key => $value) {
            if (isset($object[$key])) {
                $obj_type = gettype($object[$key]);
                if ($value['value_type'] != $obj_type) {
                    switch ($value['value_type']) {
                        case 'integer':
                            $new_object[$key] = intval($object[$key]);
                            break;
                        case 'double':
                            $new_object[$key] = floatval($object[$key]);
                            break;
                        case 'array':
                            $new_object[$key] = explode(', ', $object[$key]);
                            break;
                        case 'coords':
                            $items = explode(',', $object[$key]);
                            $new_object[$key] = [
                                floatval($items[0]),
                                floatval($items[1]),
                            ];
                            break;
                        case 'string':
                            $new_object[$key] = implode(', ', $object[$key]);
                            break;
                        case 'date':
                            if ($object[$key] instanceof \MongoDate) {
                                $new_object[$key] = $object[$key];
                            } else {
                                $new_object[$key] = new \MongoDate($object[$key]);
                            }
                            break;
                    }
                } else {
                    $new_object[$key] = $object[$key];
                }
            } else {
                $new_object[$key] = $this::$schema[$key]['default'];
            }
        }
        // Pass id and MongoId if exist
        isset($object['id']) && $new_object['id'] = intval($object['id']);
        isset($object['_id']) && $new_object['_id'] = $object['_id'];
        return $new_object;
    }

    public function add($object) {
        $this->container->insert($this->checkSchema($object));
        return true;
    }

    public function addNext($object) {
        $reflect_class = new \ReflectionClass(__CLASS__);
        $object['id'] = $this->getNextSequence(strtolower($reflect_class->getShortName()));
        return $this->add($object);
    }

    public function findOne($object) {
        return $this->container->findOne($object);
    }

    public function getByMongoId($_id) {
        $_id = Mongo::checkId($_id);
        return $this->container->findOne(['_id' => $_id]);
    }

    public function delByMongoId($_id) {
        $_id = Mongo::checkId($_id);
        return $this->container->remove(['_id' => $_id]);
    }

    public function updateByMongoId($_id, $modify) {
        $_id = Mongo::checkId($_id);
        return $this->container->update(['_id' => $_id], $modify);
    }

    public function getAll($offset=0, $max=5) {
        $skip = $max * $offset;

        $cursor = $this->container->find()->sort(['_id' => -1]);
        $count = $cursor->count();

        return [static::clearMongo(iterator_to_array(
            $cursor->skip($skip)->limit($max)
        )), $count];
    }

    public function query($query, $offset=0, $max=5) {
        $skip = $max * $offset;

        $cursor = $this->container->find($query)->sort(['_id' => -1]);
        $count = $cursor->count();

        return [static::clearMongo(iterator_to_array(
            $cursor->skip($skip)->limit($max)
        )), $count];
    }

    public function getById($id) {
        return Mongo::clearMongo($this->container->findOne(['id' => intval($id)]));
    }

    public function updateById($id, $object) {
        return $this->container->update(['id' => intval($id)], $this->checkSchema($object));
    }

    public function delById($id) {
        return $this->container->remove(['id' => intval($id)]);
    }

    public function collect($resource) {
        return $this->checkSchema($resource);
    }

    private function getNextSequence($name){
        $retval = $this->counters->findAndModify(
            ['_id' => $name],
            ['$inc' => ["seq" => 1]],
            null,
            ["new" => true]
        );

        if (!isset($retval['seq'])) {
            $this->counters->insert([
                '_id' => $name,
                'seq' => 0
            ]);
            return $this->getNextSequence($name);
        }
        return $retval['seq'];
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