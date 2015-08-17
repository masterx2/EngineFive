<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/9/15
 * Time: 12:16 AM
 */

namespace App\Models;

class Post extends Model {
    public static $schema = [
        'id' => [
            'default' => 0,
            'value_type' => 'integer',
            'control_type' => 'hidden'
        ],
        'title' => [
            'default' => 'Untitled',
            'value_type' => 'string',
            'control_type' => 'input'
        ],
        'section' => [
            'default' => 'main',
            'value_type' => 'string',
            'control_type' => 'input',
        ],
        'created' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input',
        ],
        'author' => [
            'default' => 'Unknown',
            'value_type' => 'string',
            'control_type' => 'input'
        ],
        'comments' => [
            'default' => [],
            'value_type' => 'array'
        ],
        'tags' => [
            'default' => [],
            'value_type' => 'array',
            'control_type' => 'input',
        ],
        'preambula' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'textarea'
        ],
        'body' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'textarea'
        ]
    ];

    public function getTags() {
        return $this->container->aggregate([
            ['$unwind' => '$tags'],
            ['$group' =>
                [
                    '_id' => '$tags',
                    'count' => [ '$sum' => 1]
                ]
            ],
            ['$sort' => ['count' => -1]]
        ])['result'];
    }
}