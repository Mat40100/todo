<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TaskService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Task $task
     */
    public function toggle(Task $task)
    {
        $task->toggle(!$task->isDone());
    }

    public function isRightUser(Task $task)
    {
        if($task->getUser() === $this->security->getUser()) {
            return true;
        }

        if($task->getUser() === null && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}