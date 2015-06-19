<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/23/15
 * Time: 8:16 PM
 */

namespace App\Models;

use App\Models\Db\Mongo;

class Visitor extends Mongo {
    public static $schema = [
        'created' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input',
        ],
        'sid' => [
            'default' => [],
            'value_type' => 'array',
            'control_type' => 'select'
        ],
        'ip' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'input'
        ],
        'hits' => [
            'default' => 0,
            'value_type' => 'integer',
            'control_type' => 'input'
        ],
        'last_activity' => [
            'default' => '',
            'value_type' => 'date',
            'control_type' => 'input',
        ],
        'agent' => [
            'default' => '',
            'value_type' => 'string',
            'control_type' => 'input'
        ]
    ];

    public function __construct($sid) {
        parent::__construct();
        if (isset ($sid)) {
            $this->sid = $sid;
        } else {
            $this->sid = $this->createVisitor();
        }
    }

    public function createVisitor() {
        $visitor = [
            'created' => new \MongoDate(),
            'sid' => self::generateSessionID(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'hits' => 1,
            'last_activity' => new \MongoDate(),
            'agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        $this->addNext($visitor);
        setcookie("sid", $visitor['sid'], strtotime('+10 years'));
        return $visitor['sid'];
    }

    public function updateSession() {
        $session_data = $this->findOne([
            'sid' => $this->sid
        ]);

        if ($session_data) {
            $this->updateByMongoId(
                $session_data['_id'],
                [
                    '$set' =>
                        ['last_activity' => new \MongoDate()],
                    '$inc' =>
                        ['hits' => 1]
                ]);
            return $this->sid;
        } else {
            return $this->createVisitor();
        }
    }

    public function getVisitor() {
        $session_data =  $this->findOne([
            'sid' => $this->sid
        ]);
        return $session_data ? Mongo::clearMongo($session_data) : false;
    }

    public function getAllVisitors() {
        $sessions_cursor = $this->getAll();
        $result = [];
        foreach ($sessions_cursor as $session) {
            $result[$session['sid']] = Mongo::clearMongo($session);
        }
        return $result;
    }

    static public function generateSessionID() {
        return md5(
            $_SERVER['HTTP_USER_AGENT']
            .'THIS_IS_SALT'
            .$_SERVER['REMOTE_ADDR']
        ).uniqid();
    }
}