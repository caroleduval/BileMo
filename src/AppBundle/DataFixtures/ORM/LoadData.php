<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use AppBundle\Entity\Phone;

class LoadData implements ORMFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $phone = new Phone();
        $phone->setBrand('Apple');
        $phone->setModel('iPhone X');
        $phone->setReference('IPHXSIL64');
        $phone->setOpSystem('iOS');
        $phone->setStorage(64);
        $phone->setColor('argent');
        $phone->setDescription('Smartphone débloqué 4G - Ecran 5,8 pouces - 64 Go - Nano-SIM - iOS - Argent');
        $em->persist($phone);

        $phone = new Phone();
        $phone->setBrand('Samsung');
        $phone->setModel('Galaxy Note8');
        $phone->setReference('GALN8BLK64');
        $phone->setOpSystem('Android');
        $phone->setStorage(64);
        $phone->setColor('noir');
        $phone->setDescription('Smartphone débloqué 4G - Ecran 6,3 pouces - 64 Go - 6 Go RAM - Simple Nano-SIM - Android Nougat 7.11 - Noir Carbone');
        $em->persist($phone);

        $phone = new Phone();
        $phone->setBrand('Sony');
        $phone->setModel('Xperia XZ1');
        $phone->setReference('XZ1BLK64');
        $phone->setOpSystem('Android');
        $phone->setStorage(64);
        $phone->setColor('noir');
        $phone->setDescription('Smartphone débloqué 4G - Ecran 5,2 pouces - 64 Go - Nano - Dual -SIM - Android - Noir');
        $em->persist($phone);

        $phone = new Phone();
        $phone->setBrand('Huawei');
        $phone->setModel('P10 Plus');
        $phone->setReference('P10SILK128');
        $phone->setOpSystem('Android');
        $phone->setStorage(128);
        $phone->setColor('argent');
        $phone->setDescription('Smartphone débloqué 4G - Ecran 5,2 pouces - 128 Go - Nano - Dual -SIM - Android - Argent');
        $em->persist($phone);

        $client1 = new Client();
        $client1->setName('SoLuxe');
        $em->persist($client1);

        $client2 = new Client();
        $client2->setName('MyPhone');
        $em->persist($client2);

        $em->flush();

        $user = new User();
        $user->setUsername('CAvêque');
        $user->setClient($client1);
        $user->setEmail('admin@soluxe.fr');
        $user->setPassword('motdepasse');
        $user->setRoles('ROLE_ADMIN');
        $user->setIsActive(1);
        $em->persist($user);

        $user = new User();
        $user->setUsername('MMartel');
        $user->setClient($client2);
        $user->setEmail('mmartel@email.fr');
        $user->setPassword('motdepasse');
        $user->setRoles('ROLE_ADMIN');
        $user->setIsActive(1);
        $em->persist($user);

        $user = new User();
        $user->setUsername('Monnet');
        $user->setClient($client1);
        $user->setEmail('mmonnet@email.fr');
        $user->setPassword('motdepasse');
        $user->setRoles('ROLE_USER');
        $user->setIsActive(1);
        $em->persist($user);

        $em->flush();
    }
}