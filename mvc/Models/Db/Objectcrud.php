<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/10/15
 * Time: 10:15 PM
 */

namespace App\Models\Db;

trait Objectcrud {

    public $counters;
    public $container;
    public static $schema;

    /**

    Schema Field Types

    default: Default value,
    value_type: Db Store type,       <--\ Will be converted
    control_type: From control type, >--/
    source_object: Fill control source,
    subsection: Subsection of source object

     **/

    public static function checkSchema($object) {
        // Fill Object by original schema
        // Convert types from controls to db types
        $new_object = [];
        foreach (self::$schema as $key => $value) {
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
                            if ($object[$key] == '') {
                                $new_object[$key] = new \MongoDate();
                            } else {
                                $new_object[$key] = new \MongoDate($object[$key]);
                            }
                            break;
                    }
                } else {
                    $new_object[$key] = $object[$key];
                }
            } else {
                $new_object[$key] = self::$schema[$key]['default'];
            }
        }
        // Pass id if exist
        isset($object['id']) && $new_object['id'] = intval($object['id']);
        return $new_object;
    }

    function add($object) {
        return $this->container->insert(self::checkSchema($object));
    }

    function getByMongoId($_id) {
        $_id = Mongo::checkId($_id);
        return $this->container->findOne(['_id' => $_id]);
    }

    function delByMongoId($_id) {
        $_id = Mongo::checkId($_id);
        return $this->container->remove(['_id' => $_id]);
    }

    function updateByMongoId($_id, $object) {
        $_id = Mongo::checkId($_id);
        return $this->container->update(['_id' => $_id], self::checkSchema($object));
    }

    function getAll($offset=0, $max=5) {
        $skip = $max * $offset;

        $cursor = $this->container->find()->sort(['_id' => -1]);
        $count = $cursor->count();

        return [Mongo::clearMongo(iterator_to_array(
            $cursor->skip($skip)->limit($max)
        )), $count];
    }

    function query($query, $offset=0, $max=5) {
        $skip = $max * $offset;

        $cursor = $this->container->find($query)->sort(['_id' => -1]);
        $count = $cursor->count();

        return [Mongo::clearMongo(iterator_to_array(
            $cursor->skip($skip)->limit($max)
        )), $count];
    }

    function getById($id) {
        return Mongo::clearMongo($this->container->findOne(['id' => intval($id)]));
    }

    function updateById($id, $object) {
        return $this->container->update(['id' => intval($id)], self::checkSchema($object));
    }

    function delById($id) {
        return $this->container->remove(['id' => intval($id)]);
    }

    function addNext($object) {
        $reflect_class = new \ReflectionClass(__CLASS__);
        $object['id'] = $this->getNextSequence(strtolower($reflect_class->getShortName()));
        return $this->add($object);
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
}