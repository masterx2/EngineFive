<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/8/15
 * Time: 11:19 PM
 */

namespace App\Models;

use App\Models\Db\Mongo,
    App\Models\Db\Objectcrud;

class Collector extends Mongo {
    use Objectcrud;

    public static $schema = [
        'check_id' => [
            'default' => 0,
            'value_type' => 'integer',
            'control_type' => 'input'
        ],
        'loc_net' => [
            'default' => [],
            'value_type' => 'coords',
            'control_type' => 'input'
        ],
        'loc_gps' => [
            'default' => [],
            'value_type' => 'coords',
            'control_type' => 'input'
        ],
        'acc_net' => [
            'default' => 999999,
            'value_type' => 'integer',
            'control_type' => 'input'
        ],
        'acc_gps' => [
            'default' => 999999,
            'value_type' => 'integer',
            'control_type' => 'input'
        ],
        'fix_net' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input'
        ],
        'fix_gps' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input'
        ],
        'created' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input',
        ]
    ];
}