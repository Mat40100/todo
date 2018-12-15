<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 11/12/18
 * Time: 12:03
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $adminClient;
    private $userClient;
    private $randomClient;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->adminClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Mathieu',
            'PHP_AUTH_PW'   => 'kinder1234',
        ));

        $this->userClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Emilie',
            'PHP_AUTH_PW'   => 'kinder1234',
        ));

        $this->randomClient = static::createClient();
    }

    public function testList()
    {
        $this->randomClient->request('GET', '/users');
        $this->assertRegExp('/\/login$/', $this->randomClient->getResponse()->headers->get('location'));

        $this->userClient->request('GET', '/users');
        $this->assertSame(403, $this->userClient->getResponse()->getStatusCode());

        $this->adminClient->request('GET', '/users');
        $this->assertSame(200, $this->adminClient->getResponse()->getStatusCode());
    }

    public function testView()
    {
        $crawler = $this->userClient->request('GET', '/profile');
        $this-> assertSame(1, $crawler->filter("html:contains('Emilie')")->count());
    }

    public function testCreate()
    {
        $crawler = $this->randomClient->request('GET', '/users/create');
        $this->assertSame(200, $this->randomClient->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'Testeur';
        $form['user[password][first]'] = 'kinder1234';
        $form['user[password][second]'] = 'kinder1234';
        $form['user[email]'] = 'test@testeur.com' ;

        $this->randomClient->submit($form);
        $crawler = $this->randomClient->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Superbe")')->count());
    }

    public function testEdit()
    {
        $crawler = $this->adminClient->request('GET', '/users');

        $link = $crawler->filter('tr:contains("Testeur")')->selectLink('Edit')->link();

        $crawler = $this->adminClient->click($link);

        $this->assertSame(1, $crawler->filter('html:contains("Modifier")')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = 'Testeur Modifié !';
        $form['user[password][first]'] = 'kinder1234';
        $form['user[password][second]'] = 'kinder1234';
        $form['user[email]'] = 'test@testeur.com' ;

        $this->adminClient->submit($form);
        $crawler = $this->adminClient->followRedirect();

        $this->assertSame(1,$crawler->filter("html:contains('Testeur Modifié !')")->count());
    }

    public function testDelete()
    {
        $crawler = $this->adminClient->request('GET', '/users');

        $link = $crawler->filter('tr:contains("Testeur Modifié !")')->selectLink('Supprimer')->link();

        $this->adminClient->click($link);
        $crawler = $this->adminClient->followRedirect();

        $this->assertSame(0 , $crawler->filter("html:contains('Testeur Modifié !')")->count());
    }
}