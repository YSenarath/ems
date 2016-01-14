<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 1/13/2016
 * Time: 2:47 PM
 */

namespace AppBundle\Controller\security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class LoginSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    protected $container;

    public function __construct(HttpUtils $httpUtils, ContainerInterface $cont, array $options)
    {
        parent::__construct($httpUtils, $options);
        $this->container=$cont;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user=$token->getUser();

        $connection = $this->container->get('database_connection');
        $sessionsController = new SessionsController($connection);

        $sessionsController->createNewSession($user->getUserName());
        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
    }
}