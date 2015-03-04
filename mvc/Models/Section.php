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

class Section extends Mongo {
    use Objectcrud;

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