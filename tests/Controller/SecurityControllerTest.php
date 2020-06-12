<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        //! selectButton entry is the buttton value and not his class or other
        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = $_SERVER['USER_EMAIL_TEST'];
        $form['password'] = $_SERVER['USER_PASSWORD_TEST'];

        $crawler = $client->submit($form);

        // update client request and test home page
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLogout()
    {
        $client = static::createClient();

        $user = static::$container
            ->get(UserRepository::class)
            ->findOneByEmail($_SERVER['USER_EMAIL_TEST']);

        $client->loginUser($user);
        $client->request('GET', '/logout');
        $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
