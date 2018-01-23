<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    public function testGetPhonesList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/phones');
        $datas=$client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
//        $items=$data['_embedded']['items'];
//        $this->assertEquals(4, count($items));
    }

    public function testGetPhone()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/phones/2');
        $datas=$client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $this->assertArrayHasKey('reference', $data);
        $this->assertEquals('Samsung',$data['brand']);
    }

}