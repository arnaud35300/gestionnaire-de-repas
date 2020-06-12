<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        //! selectButton entry is the buttton value and not his class or other
        $form = $crawler->selectButton('Sign in')->form();
        
        $form['email'] = $_SERVER['EMAIL_TEST'];
        $form['password'] = $_SERVER['PASSWORD_TEST'];

        $crawler = $client->submit($form);

        // update client request and test home page
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
