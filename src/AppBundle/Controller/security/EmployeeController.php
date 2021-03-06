<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
// src/AppBundle/Controller/security/EmployeeController.php
namespace AppBundle\Controller\security;

use AppBundle\Entity\security\Employee;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class EmployeeController extends Controller
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $employee_id
     * @return bool | Employee
     */
    public function searchEmployee($employee_id)
    {
        $sql = 'SELECT * FROM employee WHERE employee_id = ?';
        $result = $this->connection->fetchAssoc($sql, array($employee_id));
        if ($result != null) {
            $employee = new Employee();
            $employee->setEmployeeId($result['employee_id']);
            $employee->setFirstName($result['first_name']);
            $employee->setLastName($result['last_name']);
            $employee->setNIC($result['NIC']);
            $employee->setTelNo($result['tel_no']);
            return $employee;
        }
        return false;
    }


    /**
     * @param Employee $employee
     */
    public function addEmployee(Employee $employee)
    {
        $this->connection->beginTransaction();
        try {
            $sql = 'INSERT INTO employee(employee_id, first_name, last_name, NIC, tel_no) VALUES (?,?,?,?,?)';
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(1, $employee->getEmployeeId());
            $statement->bindValue(2, $employee->getFirstName());
            $statement->bindValue(3, $employee->getLastName());
            $statement->bindValue(4, $employee->getNIC());
            $statement->bindValue(5, $employee->getTelNo());
            $statement->execute();

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
        }
    }


    /**
     * @param Employee $employee
     */
    public function updateEmployee(Employee $employee)
    {
        $this->connection->beginTransaction();
        try {
            $sql = 'UPDATE employee SET first_name=?, last_name=?, NIC=?, tel_no=? WHERE employee_id=?';
            $statement = $this->connection->prepare($sql);

            $statement->bindValue(1, $employee->getFirstName());
            $statement->bindValue(2, $employee->getLastName());
            $statement->bindValue(3, $employee->getNIC());
            $statement->bindValue(4, $employee->getTelNo());
            $statement->bindValue(5, $employee->getEmployeeId());

            $statement->execute();

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }

    /**
     *
     */
    public function getAllEmployees()
    {
        $sql = 'SELECT * FROM employee';
        $result = $this->connection->executeQuery($sql);
        $result = $result->fetchAll();

        $employees = array();

        foreach ($result as $a) {
            if ($a != null) {
                $employee = new Employee();
                $employee->setEmployeeId($a['employee_id']);
                $employee->setFirstName($a['first_name']);
                $employee->setLastName($a['last_name']);
                $employee->setNIC($a['NIC']);
                $employee->setTelNo($a['tel_no']);
                $employees[] = $employee;
            }
        }

        return $employees;
    }

    public function deleteEmployee($employee_id)
    {
        $this->connection->beginTransaction();
        try {
            $sql = 'DELETE FROM employee WHERE employee_id=?';
            $statement = $this->connection->prepare($sql);

            $statement->bindValue(1, $employee_id);

            $statement->execute();

            $this->connection->commit();
            return true;
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->connection->rollBack();
            return false;
        }
    }
}
