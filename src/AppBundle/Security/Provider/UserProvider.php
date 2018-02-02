<?php

namespace AppBundle\Security\Provider;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Doctrine\ORM\EntityManager;

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
            throw new UsernameNotFoundException($message, 0, $failed);
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (false == $this->supportsClass($class)) {
            throw new UnsupportedException(
                sprintf(
                    'Instances of "%s" are not supported',
                    $class
                )
            );
        }
        return $this->userRepository->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->class === $class
            || is_subclass_of($class, $this->class);

    }
}