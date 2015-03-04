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

class Comment extends Mongo {
    use Objectcrud;
    public static $schema = [
        'post_id' => [
            'default' => 9999,
            'value_type' => 'int',
            'control_type' => 'hidden'
        ],
        'created' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'hidden',
        ],
        'email' => [
            'default' => 'Unknown',
            'value_type' => 'string',
            'control_type' => 'input'
        ],
        'body' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'textarea'
        ]
    ];
}