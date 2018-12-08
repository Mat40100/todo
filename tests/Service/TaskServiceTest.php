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
        $userAdmin = new User();
            $userAdmin->setRoles('ROLE_ADMIN');
        $userSimple = new User();
            $userSimple->setRoles('ROLE_USER');

        $taskAnon = new Task(); /** Use to test anonymous task @var $task */
        $task = new Task(); /** Use to test user's task */
        $task->setUser($userSimple);

        $service = new TaskService();

        $this->assertTrue($service->isRightUser($task, $userSimple));
        $this->assertTrue($service->isRightUser($taskAnon, $userAdmin));
        $this->assertFalse($service->isRightUser($taskAnon, $userSimple));
        $this->assertFalse($service->isRightUser($task, $userAdmin));
    }
}