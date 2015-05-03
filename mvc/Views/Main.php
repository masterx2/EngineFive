<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/16/15
 * Time: 7:49 PM
 */

namespace App\Views;

class Main extends Common {
    public function getForm($schema, $scenario, $data=[]) {
        // Convert DB Data to Form
        $ct2dt = [ // Control-type to Data-type
            'input' => 'string',
            'hidden' => 'string',
            'textarea' => 'string'
        ];
        $new_object = [];
        foreach ($schema as $key => $value) {
            if (isset($data[$key])) {
                $obj_type = gettype($data[$key]);
                if (isset($value['control_type']) && $ct2dt[$value['control_type']] != $obj_type) {
                    switch ($value['value_type']) {
                        case 'array':
                            $new_object[$key] = implode(', ', $data[$key]);
                            break;
                        case 'string':
                            $new_object[$key] = explode(', ', $data[$key]);
                            break;
                        case 'date':
                            $new_object[$key] = $data[$key];
                            break;
                    }
                } else {
                    $new_object[$key] = $data[$key];
                }
            }
        }
        // Pass id if exist
        isset($data['id']) && $new_object['id'] = $data['id'];
        return $this->fenom->fetch('form_generator.tpl', [
            'schema' => $schema,
            'data' => $new_object,
            'scenario' => $scenario
        ]);
    }
}

