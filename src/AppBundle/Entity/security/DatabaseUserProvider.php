<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:09 PM
 */
// src/AppBundle/Entity/security/DatabaseUserProvider.php

namespace AppBundle\Entity\security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use AppBundle\Controller\security\UserController;

class DatabaseUserProvider implements UserProviderInterface
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.
        $userController = new UserController($this->connection);

        $userData = $userController->userSearchAction($username);

        if ($userData) {
            $id = $userData['employee_id'];
            $password = $userData['password'];
            $privilege_level = $userData['privilege_level'];
            $roles = Array();
            if ($privilege_level == 0)
                $roles[] = "ROLE_ADMIN";
            elseif ($privilege_level == 1)
                $roles[] = "ROLE_MNGR";
            else
                $roles[] = 'ROLE_USER';
            return new DatabaseUser($id, $username, $password, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );

    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
        if (!$user instanceof DatabaseUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
        return $class === 'AppBundle\Entity\security\DatabaseUser';
    }
}