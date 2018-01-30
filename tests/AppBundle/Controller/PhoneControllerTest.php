<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    private function getToken()
    {
        $oauthHeaders = [
            "client_id" => "1_45n6woxab000o8oc0w0oksgwo8s44w0wkokkcgsc0kwggos8gk",
            "client_secret" => "3jzxkn39e3okgs40o48ssg80wgcsww4gwgco8k4ko80g08ows0",
            "grant_type" => "password",
            "username" => "Admin_SL",
            "password" => "motdepasse"
        ];

        $crawler = $this->client->request('GET', '/oauth/v2/token', $oauthHeaders);
        $data = $this->client->getResponse()->getContent();
        $json = json_decode($data);
        $accessToken= $json->{'access_token'};

        return $accessToken;
    }

    public function testGetPhonesListwithoutToken()
    {
        $crawler = $this->client->request('GET', '/phones');
        $datas=$this->client->getResponse();

        $this->assertEquals(401, $datas->getStatusCode());
    }

    public function testGetPhonesList()
    {
        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $crawler = $this->client->request('GET', '/phones', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $items=$data['_embedded']['items'];
        $this->assertEquals(4, count($items));
    }

    public function testGetPhone()
    {
        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $crawler = $this->client->request('GET', '/phones/2', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $this->assertArrayHasKey('reference', $data);
        $this->assertEquals('Samsung',$data['brand']);
    }

}
