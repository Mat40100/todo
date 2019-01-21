<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;

class TaskService
{
    /**
     * @param Task      $task
     * @param User|null $user
     *
     * @return bool
     */
    public function isRightUser(Task $task, ?User $user)
    {
        if ($task->getUser() === $user && null != $user) {
            return true;
        }

        if (null === $task->getUser() && in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        return false;
    }
}
