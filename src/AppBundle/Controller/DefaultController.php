<?php

namespace AppBundle\Controller;

use AppBundle\Controller\security\EmployeeController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\security\DatabaseUser;
use AppBundle\Controller\security\UserController;
use AppBundle\Form\UserType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        return $this->render(
            'AppBundle::index.html.twig'
        );
    }

    /**
     * @Route("/home", name="systemHome")
     */
    public function systemHomeAction(Request $request)
    {
        //return new Response("<h1>Welcome</h1>");
        return $this->render(
            'AppBundle::home.html.twig'
        );
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render(
            'AppBundle::about.html.twig'
        );
    }

    /**
     * @Route("/register", name="register")
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

            // save the User
            $connection = $this->get('database_connection');
            $userController = new UserController($connection);
            $employeeController = new EmployeeController($connection);
            if ($employeeController->employeeSearchAction($user->getId())) {
                $userController->userAddAction(
                    $user->getId(),
                    $user->getUsername(),
                    $user->getPassword(),
                    $user->getRoleId());
                return $this->redirectToRoute('homepage');
            } else {
                return $this->redirectToRoute('register');
            }
        }
        return $this->render(
            'AppBundle:security:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
