<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/8/15
 * Time: 11:19 PM
 */

namespace App\Models;

class Section extends Common {
    public static $schema = [
        'title' => [
            'default' => 'Untitled',
            'value_type' => 'string',
            'control_type' => 'input'
        ],
        'subsection' => [
            'default' => null,
            'value_type' => 'string',
            'control_type' => 'input'
        ],
        'created' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input',
        ]
    ];
}