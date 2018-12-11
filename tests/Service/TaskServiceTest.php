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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskServiceTest extends WebTestCase
{
    public function testIsRightUser()
    {
        $service = new TaskService();

        $userAdmin = new User();
            $userAdmin->setRoles('ROLE_ADMIN');
        $userSimple = new User();
            $userSimple->setRoles('ROLE_USER');

        $task = new Task();
            $task->setUser($userSimple);

        $taskEmpty = new Task();

        $this->assertTrue($service->isRightUser($task, $userSimple));
        $this->assertFalse($service->isRightUser($task, $userAdmin));
        $this->assertFalse($service->isRightUser($task, null));
        $this->assertTrue($service->isRightUser($taskEmpty, $userAdmin));
    }
}