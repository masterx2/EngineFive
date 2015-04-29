<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/8/15
 * Time: 11:19 PM
 */

namespace App\Models;

class Comment extends Common {
    public $schema = [
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