<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\Client;

class Approver
{
    public function isGranted(User $user, User $admin)
    {
        if ($user->getClient()->getId()==$admin->getClient()->getId()){
            return true;
        }
        else {
            return false;
        }
    }
}
