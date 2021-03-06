<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
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

    public function testAttemptUserswithoutToken()
    {
        $this->client->request('GET', '/users');
        $datas=$this->client->getResponse();

        $this->assertEquals(401, $datas->getStatusCode());
    }

    public function testAttemptUsersAsUser()
    {
        $accessToken = $this->getToken("User_SoLuxe1");

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json'
        );

        $this->client->request('GET', '/users', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(403, $datas->getStatusCode());
    }

    public function testGetUsersList()
    {
        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('GET', '/users', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);
        $items=$data['_embedded']['items'];
        $this->assertEquals(3, count($items));
    }

    public function testGetUser()
    {
        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('GET', '/users/3', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(200, $datas->getStatusCode());
        $data = json_decode($datas->getContent(), true);

        $this->assertEquals('User_SoLuxe1',$data['username']);
    }

    public function testCreateUser()
    {
        $data = array(
            'username'=> 'new_Username',
            'email'=> 'new@email.fr',
            'password'=> 'motdepasse'
        );

        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('POST', '/users', array(), array(), $headers, json_encode($data));
        $datas=$this->client->getResponse();

        $this->assertEquals(201, $datas->getStatusCode());
    }

    public function testCreateAdmin()
    {
        $data = array(
            'username'=> 'new_Admin',
            'email'=> 'newA@email.fr',
            'password'=> 'motdepasse'
        );

        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('POST', '/users?role=admin', array(), array(), $headers, json_encode($data));
        $datas=$this->client->getResponse();

        $this->assertEquals(201, $datas->getStatusCode());
    }

    public function testDeleteUser()
    {
        $accessToken = $this->getToken();

        $headers = array(
            'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
            'CONTENT_TYPE' => 'application/json',
        );

        $this->client->request('DELETE', '/users/6', array(), array(), $headers);
        $datas=$this->client->getResponse();

        $this->assertEquals(204, $datas->getStatusCode());
    }
}