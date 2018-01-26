<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;

class UserControllerTest extends WebTestCase
{
    public function testGetUsersList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');
        $datas=$client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $items=$data['_embedded']['items'];
        $this->assertEquals(3, count($items));
    }

    public function testGetUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/2');
        $datas=$client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $this->assertArrayHasKey('first_name', $data);
        $this->assertEquals('Martel',$data['name']);
    }

//    public function testCreateUser()
//    {
//        $data = array(
//            'name'=> 'Nom',
//            'first_name'=> 'Prenom',
//            'gender'=> 'Monsieur',
//            'client'=> array(
//                'name'=> 'SoLuxe',
//                'email'=> 'admin@soluxe.com',
//                'password'=> 'motdepasse',
//                'address'=> '113 rue de Rivoli',
//                'town'=> 'Paris',
//                'postcode'=> '75001'
//            ),
//            'email'=> 'pnom@email.fr',
//            'password'=> 'motdepasse',
//            'address'=> 'adresse',
//            'town'=> 'Ville',
//            'postcode'=> '99999'
//        );
//
//        $client = static::createClient();
//        $client->request(
//            'POST',
//            '/users',
//            array(),
//            array(),
//            array('CONTENT_TYPE' => 'application/json'),
//            $data);
//        $reponse=$client->getResponse();
//
//        $this->assertEquals(201, $reponse->getStatusCode());
//        $this->assertContains('application/json', $reponse->headers);
//    }
//
//    public function testDeleteUser()
//    {
//        $client = static::createClient();
//        $crawler = $client->request('DELETE', '/users/2');
//        $datas=$client->getResponse();
//
//        $this->assertEquals(202, $datas->getStatusCode());
//    }
}