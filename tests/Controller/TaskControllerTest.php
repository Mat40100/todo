<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 10/12/18
 * Time: 19:38.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $adminClient;
    private $userClient;
    private $randomClient;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->adminClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Mathieu',
            'PHP_AUTH_PW' => 'kinder1234',
        ));

        $this->userClient = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Emilie',
            'PHP_AUTH_PW' => 'kinder1234',
        ));

        $this->randomClient = static::createClient();
    }

    public function testTasksCreate()
    {
        $crawler = $this->userClient->request('GET', '/tasks/create');
        $this->assertSame(1, $crawler->filter('html:contains("Ajouter")')->count());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'Test fonctionnel';
        $form['task[content]'] = 'Il fonctionne !';

        $this->userClient->submit($form);
        $crawler = $this->userClient->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Test fonctionnel")')->count());
    }

    public function testTasksEdit()
    {
        $crawler = $this->adminClient->request('GET', '/tasks');
        $this->adminClient->click($crawler->filter("a:contains('Test fonctionnel')")->link());
        $this->assertEquals(403, $this->adminClient->getResponse()->getStatusCode());

        $crawler = $this->userClient->request('GET', '/tasks');

        $crawler = $this->userClient->click($crawler->filter("a:contains('Test fonctionnel')")->link());

        $this->assertSame(1, $crawler->filter("html:contains('Modifier')")->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['task[content]'] = 'Il est modifié !';

        $this->userClient->submit($form);
        $crawler = $this->userClient->followRedirect();

        $this->assertSame(1, $crawler->filter("html:contains('Il est modifié !')")->count());
    }

    public function testTaskToggle()
    {
        $crawler = $this->userClient->request('GET', '/tasks');

        $form = $crawler->filter("html:contains('Test fonctionnel')")->selectButton('Marquer comme faite')->form();

        $this->userClient->submit($form);
        $crawler = $this->userClient->followRedirect();
        $this->assertSame(1, $crawler->filter("html:contains('Marquer non terminée')")->count());

        $form = $crawler->filter("html:contains('Test fonctionnel')")->selectButton('Marquer non terminée')->form();

        $this->userClient->submit($form);
        $crawler = $this->userClient->followRedirect();

        $this->assertSame(1, $crawler->filter("html:contains('Marquer comme faite')")->count());
    }

    public function testTasksDelete()
    {
        $crawler = $this->adminClient->request('GET', '/tasks');
        $form = $crawler->filter('html:contains("Test fonctionnel")')->selectButton('Supprimer')->form();

        $this->adminClient->submit($form);

        $this->assertSame(403, $this->adminClient->getResponse()->getStatusCode());

        $crawler = $this->userClient->request('GET', '/tasks');

        $form = $crawler->filter('html:contains("Test fonctionnel")')->selectButton('Supprimer')->form();

        $this->userClient->submit($form);
        $crawler = $this->userClient->followRedirect();

        $this->assertSame(0, $crawler->filter('html:contains("Test fonctionnel")')->count());
    }
}
