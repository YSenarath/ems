<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 1/13/2016
 * Time: 2:09 PM
 */

namespace AppBundle\Controller\security;

use AppBundle\Entity\security\Session;

class SessionsController
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $username
     */
    public function createNewSession($username) {
        $this->connection->beginTransaction();
        try {
            $sql = 'INSERT INTO `session` (`user_name`) VALUES (?)';
            $statement = $this->connection->prepare($sql);

            $statement->bindValue(1, $username);

            $statement->execute();

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }

    /**
     * @return array
     */
    public function getTopSessions()
    {
        $result = $this->connection->executeQuery('SELECT * FROM `session` ORDER BY start_time DESC LIMIT 5');
        $result = $result->fetchAll();

        //print_r($result);
        $sessions = array();

        foreach ($result as $s) {
            if ($s != null) {
                $session = new Session();
                $session->setSessionId($s['session_id']);
                $session->setUserName($s['user_name']);
                $session->setStartTime($s['start_time']);
                $sessions[] = $session;
            }
        }

        return $sessions;
    }
}