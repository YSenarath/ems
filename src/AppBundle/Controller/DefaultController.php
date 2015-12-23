<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\security\EmployeeController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\security\DatabaseUser;
use AppBundle\Controller\security\UserController;
use AppBundle\Controller\location\LocationController;
use AppBundle\Form\security\UserType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

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
                if (!$userController->userSearchAction($user->getUsername())) {
                    $userController->userAddAction(
                        $user->getId(),
                        $user->getUsername(),
                        $user->getPassword(),
                        $user->getRoleId());
                } else {
                    return $this->redirectToRoute('register');
                }
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

    //by dulanjaya
    /**
     * @Route("/location", name="location")
     */
    public function locationAction()
    {
        $conn = $this->get('database_connection');
        $locations = new LocationController($conn);
        $locs = $locations->getLocationsAction();
        return $this->render(
            'AppBundle:location:location.html.twig', array('locations'=>$locs)
        );
    }

    /**
     * @Route("/addlocs", name="addlocs")
     */
    public function addlocAction()
    {
        return $this->render(
            'AppBundle:location:addlocs.html.twig'
        );
    }
}
