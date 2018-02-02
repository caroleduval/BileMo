<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class Approver
{
    public function isGranted(User $user, User $admin)
    {
        if (in_array('ROLE_ADMIN', $admin->getRoles(), true) AND ($user->getClient()->getId()==$admin->getClient()->getId())){
            return true;
        }
        else {
            return false;
        }
    }
}
