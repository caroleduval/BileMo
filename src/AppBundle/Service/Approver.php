<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class Approver
{
    public function isGranted(User $user, User $admin)
    {
        $bool = false;

        if (in_array('ROLE_ADMIN', $admin->getRoles(), true) && ($user->getClient()->getId()==$admin->getClient()->getId())){
            return true;
        }

        return $bool;
    }
}
