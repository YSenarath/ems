<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
// src/AppBundle/Controller/security/EmployeeController.php
namespace AppBundle\Controller\security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmployeeController extends Controller
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function employeeSearchAction($employee_id)
    {
        $employee = $this->connection->fetchAssoc('SELECT * FROM employee WHERE employee_id = ?', array($employee_id));
        if ($employee != null)
            return $employee;
        return false;
    }

    public function userAddAction($id, $first_name, $last_name, $nic, $tel_no)
    {
        $this->connection->beginTransaction();
        try{
            $statement = $this->connection->prepare('INSERT INTO employee(employee_id, first_name, last_name, NIC, tel_no) VALUES (?,?,?,? ?,?)');
            $statement->bindValue(1, $id);
            $statement->bindValue(2, $first_name);
            $statement->bindValue(3, $last_name);
            $statement->bindValue(4, $nic);
            $statement->bindValue(4, $tel_no);
            $statement->execute();
            $this->connection->commit();
        } catch(Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }
}
