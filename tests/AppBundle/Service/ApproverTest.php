<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use AppBundle\Service\Approver;
use PHPUnit\Framework\TestCase;

class ApproverTest extends TestCase
{
    protected $approver;

    public function setUp()
    {
        $this->approver = new Approver();
    }

    public function testnotanadmin()
    {
        $user = new User();

        $notadmin = new User();
        $notadmin->setRoles(['ROLE_USER']);

        $this->assertEquals(false, $this->approver->isGranted($user, $notadmin));
    }

    public function testadminnotuser()
    {
        $client1 = $this->createMock(Client::class);
        $client1->method('getId')->willReturn('1');

        $client2 = $this->createMock(Client::class);
        $client2->method('getId')->willReturn('2');

        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setClient($client1);

        $user = new User();
        $user->setClient($client2);

        $this->assertEquals(false, $this->approver->isGranted($user, $admin));
    }

    public function testadminanduser()
    {
        $client1 = $this->createMock(Client::class);
        $client1->method('getId')->willReturn('1');

        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setClient($client1);

        $user = new User();
        $user->setClient($client1);

        $this->assertEquals(true, $this->approver->isGranted($user, $admin));
    }

}