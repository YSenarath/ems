<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
// src/AppBundle/Controller/security/SecurityController.php
namespace AppBundle\Controller\security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function userSearchAction($username)
    {
        $user = $this->connection->fetchAssoc('SELECT * FROM user_account WHERE user_name = ?', array($username));

        if ($user != null)
            return $user;
        return false;
    }

    public function userAddAction($id, $username, $password, $privilege_level)
    {
        $this->connection->beginTransaction();
        try{
            $statement = $this->connection->prepare('INSERT INTO user_account (employee_id, user_name, password, privilege_level) VALUES (?, ?, ?, ?)');
            $statement->bindValue(1, $id);
            $statement->bindValue(2, $username);
            $statement->bindValue(3, $password);
            $statement->bindValue(4, $privilege_level);
            $statement->execute();
            $this->connection->commit();
        } catch(Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }
}
