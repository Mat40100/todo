<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 07/12/2018
 * Time: 15:28
 */

namespace App\Tests\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Security;


class TaskServiceTest extends WebTestCase
{
    public function testIsRightUser()
    {
        $userAdmin = new User();
            $userAdmin->setRoles('ROLE_ADMIN');
        $userSimple = new User();
            $userSimple->setRoles('ROLE_USER');

        $taskAnon = new Task();
        $task = new Task();
        $task->setUser($userSimple);

    $this->assertTrue();
    }
}