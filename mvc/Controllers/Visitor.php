<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/23/15
 * Time: 8:16 PM
 */

namespace App\Controllers;

use App\Models\Db\Mongo;

class Visitor extends Mongo {
    public function createVisitor() {
        $sid = self::generateSessionID();

        $visitor = [
            'ctime' => new \MongoDate(),
            'sid' => $sid,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'hits' => 1,
            'last_activity' => new \MongoDate(),
            'agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        $this->container->insert($visitor);
        setcookie("sid", $sid, strtotime('+10 years'));
        return $sid;
    }

    public function updateSession($sid) {
        $session_data = $this->container->findOne([
            'sid' => $sid
        ]);

        if ($session_data) {
            $this->container->update(
                [
                    '_id' => $session_data['_id']
                ],
                [
                    '$set' =>
                        [
                            'last_activity' => new \MongoDate()
                        ],
                    '$inc' =>
                        [
                            'hits' => 1
                        ]
                ]
            );

            return $sid;
        } else {
            return $this->createVisitor();
        }
    }

    public function getVisitor($sid) {
        $session_data =  $this->container->findOne([
            'sid' => $sid
        ]);
        return $session_data ? Mongo::clearMongo($session_data) : false;
    }

    public function getAllVisitors() {
        $sessions_cursor = $this->container->find();
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