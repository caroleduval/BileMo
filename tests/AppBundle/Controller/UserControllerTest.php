<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

}