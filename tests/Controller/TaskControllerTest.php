<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 10/12/18
 * Time: 19:38
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $adminclient;
    private $userClient;
    private $randomClient;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->adminclient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Mathieu',
            'PHP_AUTH_PW'   => 'kinder1234',
        ));

        $this->userClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Emilie',
            'PHP_AUTH_PW'   => 'kinder1234',
        ));

        $this->randomClient = $client = static::createClient();

    }

    public function testHome()
    {
        $crawler = $this->adminclient->request('GET', '/');

        $this->assertSame(1, $crawler->filter('html:contains("Liste des utilisateurs")')->count());

        $crawler = $this->randomClient->request('GET', '/');

        $this->assertSame(0, $crawler->filter('html:contains("Liste des utilisateurs")')->count());
    }

    public function testList()
    {
         $this->randomClient->request('GET', '/tasks');

        $this->assertEquals(200, $this->randomClient->getResponse()->getStatusCode());
    }

    public function testTasksCreate()
    {
        $this->randomClient->request('GET', '/tasks/create');
        $crawler = $this->randomClient->followRedirect();
        $this->assertSame(1 , $crawler->filter('html:contains("Se connecter")')->count());

        $crawler = $this->userClient->request('GET', '/tasks/create');
        $this->assertSame(1 , $crawler->filter('html:contains("Ajouter")')->count());

        ##@TODO tesk[content] for form
    }
}