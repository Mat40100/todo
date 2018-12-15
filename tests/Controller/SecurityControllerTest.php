<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 15/12/18
 * Time: 10:20
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $crawler = $client->click($crawler->filter("a:contains('Se connecter')")->link());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1 , $crawler->filter('html:contains("Connection")')->count());

        $form = $crawler->selectButton('Connection')->form();

        $form['_password'] = 'kinder1234';
        $form['_username'] = 'Emilie';

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter("html:contains('Se déconnecter')")->count());

        $client->click($crawler->filter("a:contains('Se déconnecter')")->link());
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter("html:contains('Se connecter')")->count());
    }
}