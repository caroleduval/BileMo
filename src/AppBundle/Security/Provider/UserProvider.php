<?php

namespace AppBundle\Security\Provider;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserProvider implements UserProviderInterface
{
    protected $class;

    protected $userRepository;

    public function __construct(EntityManager $entityManager, $class)
    {
        $this->class = $class;
        $this->userRepository = $entityManager->getRepository($class);
    }

    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->findOneBy(array('username' => $username));
        if (null === $user) {
            $message = sprintf(
                'Unable to find an active User object identified by "%s"',
                $username
            );
            throw new NotFoundHttpException($message);
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
