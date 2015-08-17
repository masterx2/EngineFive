<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 24.07.15
 * Time: 20:45
 */

namespace App\Models;

use App\Models\Db\Mongo;

/**
 * Class Model
 * @package App\Models
 */
abstract class Model {

    public static $schema;
    public $db;
    public $counters;
    public $container;

    public function __construct() {
        $model_name = self::getBaseClassName();
        $this->container = Mongo::$db->$model_name;
        $this->counters = Mongo::$db->counters;
    }

    public static function getBaseClassName() {
        $class = explode('\\', get_called_class());
        return strtolower(array_pop($class));
    }

    /**
     * @param $object
     * @return array
     */
    public function checkSchema($object) {
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
        $object['id'] = $this->getNextSequence(self::getBaseClassName());
        return $this->add($object);
    }

    public function findOne($object) {
        return $this->container->findOne($object);
    }

    public function getByMongoId($_id) {
        $_id = self::checkId($_id);
        return $this->container->findOne(['_id' => $_id]);
    }

    public function delByMongoId($_id) {
        $_id = self::checkId($_id);
        return $this->container->remove(['_id' => $_id]);
    }

    public function updateByMongoId($_id, $modify) {
        $_id = self::checkId($_id);
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

    public function query($query, $projection=[], $offset=0, $max=5) {
        $skip = $max * $offset;

        $cursor = $this->container->find($query, $projection)->sort(['_id' => -1]);
        $count = $cursor->count();

        return [static::clearMongo(iterator_to_array(
            $cursor->skip($skip)->limit($max)
        )), $count];
    }

    public function getById($id) {
        return self::clearMongo($this->container->findOne(['id' => intval($id)]));
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

    public function getFields() {
        return array_keys($this::$schema);
    }
}