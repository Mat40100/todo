<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TaskService
{
    private $entitymanager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entitymanager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param User $user
     * @param Task $task
     */
    public function create(User $user, Task $task)
    {
        $task->setUser($user);
        $this->entitymanager->persist($task);
        $this->entitymanager->flush();
    }

    /**
     * @param Task $task
     */
    public function toggle(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->entitymanager->flush();
    }

    /**
     * @param $task
     */
    public function delete($task)
    {
        $this->entitymanager->remove($task);
        $this->entitymanager->flush();
    }

    public function isRightUser(Task $task)
    {
        if($task->getUser() === $this->security->getUser()) {
            return true;
        }

        if($task->getUser()->getUsername() === 'Anonyme' && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}