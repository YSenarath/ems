<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */

// src/AppBundle/Controller/security/SecurityController.php
namespace AppBundle\Controller\security;

use AppBundle\Entity\security\DatabaseUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $username
     * @return DatabaseUser|bool
     */
    public function searchUser($username)
    {
        $result = $this->connection->fetchAssoc('SELECT * FROM user WHERE user_name = ?', array($username));
        if ($result != null) {
            $id = $result['employee_id'];
            $password = $result['password'];
            $privilege_level = $result['privilege_level'];

            $roles = Array();

            if ($privilege_level == 0)
                $roles[] = "ROLE_ADMIN";
            elseif ($privilege_level == 1)
                $roles[] = "ROLE_MNGR";
            else
                $roles[] = 'ROLE_TECH';

            return new DatabaseUser($id, $username, $password, $roles);
        }
        return false;
    }

    /**
     * @param DatabaseUser $user
     */
    public function addUser(DatabaseUser $user)
    {
        $this->connection->beginTransaction();
        try {
            $sql = 'INSERT INTO user (employee_id, user_name, password, privilege_level) VALUES (?, ?, ?, ?)';
            $statement = $this->connection->prepare($sql);

            $statement->bindValue(1, $user->getId());
            $statement->bindValue(2, $user->getUsername());
            $statement->bindValue(3, $user->getPassword());
            $statement->bindValue(4, $user->getRoleId());

            $statement->execute();

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }

    /**
     * @param DatabaseUser $user
     */
    public function updateUser(DatabaseUser $user)
    {
        $this->connection->beginTransaction();
        try {
            $sql = 'UPDATE user SET password=? WHERE user_name=?';
            $statement = $this->connection->prepare($sql);

            $statement->bindValue(1, $user->getPassword());
            $statement->bindValue(2, $user->getUsername());

            $statement->execute();

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }
}
