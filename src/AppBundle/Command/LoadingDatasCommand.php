<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use AppBundle\Entity\Phone;

class LoadingDatasCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import-fixtures')
            ->setDescription('Imports datas into Client, User and Phone tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

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
        $client1->setRandomId('45n6woxab000o8oc0w0oksgwo8s44w0wkokkcgsc0kwggos8gk');
        $client1->setSecret('3jzxkn39e3okgs40o48ssg80wgcsww4gwgco8k4ko80g08ows0');
        $client1->setAllowedGrantTypes(["password"]);

        $em->persist($client1);

        $client2 = new Client();
        $client2->setName('MyPhone');
        $client2->setRandomId('3yts5mebf8sgcs8k44o04kk4s4cgwccsss084g4gw0socks08s');
        $client2->setSecret('1tjx88y1m9okckcoo8o8c08gkkow0ssc8gwog00cwsoccko0w0');
        $client2->setAllowedGrantTypes(["password"]);

        $em->persist($client2);

        $em->flush();

        $user = new User();
        $user->setUsername('Admin_SL');
        $user->setClient($client1);
        $user->setEmail('admin@soluxe.fr');
        $user->setPassword('motdepasse');
        $user->setRoles(['ROLE_ADMIN']);
        $em->persist($user);

        $user = new User();
        $user->setUsername('Admin_MP');
        $user->setClient($client2);
        $user->setEmail('admin@email.fr');
        $user->setPassword('motdepasse');
        $user->setRoles(['ROLE_ADMIN']);
        $em->persist($user);

        $user = new User();
        $user->setUsername('User_SoLuxe1');
        $user->setClient($client1);
        $user->setEmail('user1@email.fr');
        $user->setPassword('motdepasse');
        $user->setRoles(['ROLE_USER']);
        $em->persist($user);

        $user = new User();
        $user->setUsername('User_SoLuxe2');
        $user->setClient($client1);
        $user->setEmail('user2@email.fr');
        $user->setPassword('motdepasse');
        $user->setRoles(['ROLE_USER']);
        $em->persist($user);

        $user = new User();
        $user->setUsername('User_MyPhone1');
        $user->setClient($client2);
        $user->setEmail('user3@email.fr');
        $user->setPassword('motdepasse');
        $user->setRoles(['ROLE_USER']);
        $em->persist($user);
        $em->flush();
    }
}
