<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 1/6/2016
 * Time: 10:40 PM
 */

namespace AppBundle\Controller\security;


use AppBundle\Form\security\UpdateEmployeeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use AppBundle\Entity\security\DatabaseUser;
use AppBundle\Entity\security\Employee;
use AppBundle\Form\security\UserType;
use AppBundle\Form\security\EmployeeType;
use AppBundle\Form\security\ProfileType;

class SecurityActionController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        // build the form
        $user = new DatabaseUser();
        $form = $this->createForm(UserType::class, $user);

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            // Encode the password
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // save User
            $connection = $this->get('database_connection');
            $userController = new UserController($connection);
            $employeeController = new EmployeeController($connection);
            if ($employeeController->searchEmployee($user->getId())) {
                if (!$userController->searchUser($user->getUsername())) {
                    $userController->addUser($user);
                } else {
                    return $this->redirectToRoute('register');
                }
                return $this->redirectToRoute('homepage');
            } else {
                $form->get('id')->addError(new FormError('The employee ID does not exist'));
                return $this->render(
                    'AppBundle:security:register.html.twig',
                    array('form' => $form->createView())
                );
            }
        }

        return $this->render(
            'AppBundle:security:register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        // build the form
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            // Encode the password
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // save User
            $connection = $this->get('database_connection');
            $userController = new UserController($connection);
            $employeeController = new EmployeeController($connection);
            if ($employeeController->searchEmployee($user->getId())) {
                if ($userController->searchUser($user->getUsername())) {
                    $userController->updateUser($user);
                } else {
                    return $this->redirectToRoute('register');
                }
                return $this->redirectToRoute('homepage');
            } else {
                $form->get('employee_id')->addError(new FormError('The employee ID already exists'));
                return $this->render(
                    'AppBundle:security:register.html.twig',
                    array('form' => $form->createView())
                );
            }
        }

        return $this->render(
            'AppBundle:security:profile.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/employee", name="addEmployee")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function employeeAddAction(Request $request)
    {
        // build the form
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $connection = $this->get('database_connection');
            $employeeController = new EmployeeController($connection);
            if (!$employeeController->searchEmployee($employee->getEmployeeId())) {
                $employeeController->addEmployee($employee);
//                $this->addFlash(
//                    'notice',
//                    'Completed Successfully.'
//                );
                return $this->redirectToRoute('listEmployees');
            }
            else {
                $form->get('employee_id')->addError(new FormError('The employee ID already exists'));
//                $this->addFlash(
//                    'notice',
//                    'Employee exists with the same ID.'
//                );
                return $this->render(
                    'AppBundle:security:employee.html.twig',
                    array('form' => $form->createView())
                );
            }
        }

        return $this->render(
            'AppBundle:security:employee.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/employee/update", name="updateEmployee")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function employeeUpdateAction(Request $request)
    {
        $employee_id = $request->query->get('id');
        $connection = $this->get('database_connection');
        $employeeController = new EmployeeController($connection);
        // build the form
        $employee = $employeeController->searchEmployee($employee_id);

        $form = $this->createForm(UpdateEmployeeType::class, $employee);

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            if ($form->get('delete')->isClicked()) {
                $validDelete = $employeeController->deleteEmployee($employee->getEmployeeId());
                if ($validDelete) {
                    return $this->redirectToRoute('listEmployees');
                } else {
                    $this->get('session')->getFlashBag()->add('msg', 'Can\'t remove: A user exist for this employee.' );
                    $this->get('session')->getFlashBag()->add('title', 'Warning');
                }
            } else {
                if ($employeeController->searchEmployee($employee->getEmployeeId())) {
                    $employeeController->updateEmployee($employee);
//                $this->addFlash(
//                    'notice',
//                    'Completed Successfully.'
//                );
                } else {
                    $form->get('employee_id')->addError(new FormError('The employee ID does not exist'));
//                $this->addFlash(
//                    'notice',
//                    'Employee does not exist in the storage.'
//                );
//                return $this->redirectToRoute('addEmployee');
                    return $this->render(
                        'AppBundle:security:updateEmployee.html.twig',
                        array('form' => $form->createView())
                    );
                }
            }
        }

        return $this->render(
            'AppBundle:security:updateEmployee.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/employee/list", name="listEmployees")
     */
    public function listEmployeesAction()
    {
        $connection = $this->get('database_connection');
        $employeeController = new EmployeeController($connection);
        $employees = $employeeController->getAllEmployees();

        return $this->render('@App/security/employeeList.html.twig', array('employees' => $employees));
    }

    /**
     * @Route("/login/list", name="loginList")
     */
    public function listLoginAction()
    {
        $connection = $this->get('database_connection');
        $employeeController = new SessionsController($connection);
        $sessions = $employeeController->getTopSessions();

        return $this->render('@App/security/loginList.html.twig', array('sessions' => $sessions));
    }
}