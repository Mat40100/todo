<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class TaskService
{
    /**
     * @param Task $task
     * @param User $user
     * @return bool
     */
    public function isRightUser(Task $task, User $user)
    {
        if($task->getUser() === $user) {
            return true;
        }

        if($task->getUser() === null && in_array("ROLE_ADMIN", $user->getRoles())) {
            return true;
        }

        return false;
    }
}