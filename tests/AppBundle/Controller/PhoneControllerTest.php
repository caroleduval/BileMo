<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    private $client = null;

    private $accessToken = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->accessToken = $this->getToken();
    }

    private function getToken($user="Admin_SL")
    {
        $oauthHeaders = [
            "client_id" => "1_4clbzy3w8bgg08cg484oks4os8s4k0cgo0ogcw4ooks8ok8cw4",
            "client_secret" => "5xm931vihmkgo0k84c844co8gk0k4cs4080w8sgsg44owck800",
            "grant_type" => "password",
            "username" => $user,
            "password" => "password"
        ];

        $this->client->request('GET', '/oauth/v2/token', $oauthHeaders);
        $data = $this->client->getResponse()->getContent();
        $json = json_decode($data);
        $accessToken= $json->{'access_token'};

        return $accessToken;
    }

    public function testGetPhonesListwithoutToken()
    {
        $this->client->request('GET', '/phones');
        $datas=$this->client->getResponse();

        $this->assertEquals(401, $datas->getStatusCode());
    }

    public function testGetPhonesList()
    {
        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$this->accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('GET', '/phones', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $items=$data['_embedded']['items'];
        $this->assertEquals(4, count($items));
    }

    public function testGetPhone()
    {
        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$this->accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('GET', '/phones/2', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $this->assertArrayHasKey('reference', $data);
        $this->assertEquals('Samsung',$data['brand']);
    }

}
