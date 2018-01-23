<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Phone;

class LoadData implements ORMFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $CustomerRepo = $em->getRepository('AppBundle:Customer');

        // Liste des noms de catégorie à ajouter
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

        $customer1 = new Customer();
        $customer1->setName('SoLuxe');
        $customer1->setEmail('admin@soluxe.com');
        $customer1->setPassword('motdepasse');
        $customer1->setAddress('113 rue de Rivoli');
        $customer1->setTown('Paris');
        $customer1->setPostcode('75001');
        $em->persist($customer1);

        $customer2 = new Customer();
        $customer2->setName('MyPhone');
        $customer2->setEmail('admin@myphone.com');
        $customer2->setPassword('motdepasse');
        $customer2->setAddress('72 rue de Bercy');
        $customer2->setTown('Paris');
        $customer2->setPostcode('75014');
        $em->persist($customer2);

        $em->flush();

        $user = new User();
        $user->setName('Avêque');
        $user->setfirstName('Cyril');
        $user->setGender('Monsieur');
        $user->setCustomer($customer1);
        $user->setEmail('caveque@email.fr');
        $user->setPassword('motdepasse');
        $user->setAddress('25,rue du pont');
        $user->setTown('Le Mans');
        $user->setPostcode('72000');
        $em->persist($user);

        $user = new User();
        $user->setName('Martel');
        $user->setfirstName('Martine');
        $user->setGender('Madame');
        $user->setCustomer($customer2);
        $user->setEmail('mmartel@email.fr');
        $user->setPassword('motdepasse');
        $user->setAddress('12,Bvd Oyon');
        $user->setTown('Bordeaux');
        $user->setPostcode('33000');
        $em->persist($user);

        $user = new User();
        $user->setName('Monnet');
        $user->setfirstName('Marine');
        $user->setGender('Madame');
        $user->setCustomer($customer1);
        $user->setEmail('mmonnet@email.fr');
        $user->setPassword('motdepasse');
        $user->setAddress('5,rue de Malidor');
        $user->setTown('Tours');
        $user->setPostcode('37000');
        $em->persist($user);

        $em->flush();
    }
}