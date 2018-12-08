<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks")
     */
    public function list()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository(Task::class)->findAll()]);
    }

    /**
     * @Route("/tasks/create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('app_task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit")
     */
    public function edit(Task $task, Request $request, TaskService $taskService)
    {
        if ($taskService->isRightUser($task, $this->getUser())) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('app_task_list');
            }

            return $this->render('task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);
        }

        throw new AccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action');
    }

    /**
     * @Route("/tasks/{id}/toggle")
     * @IsGranted("ROLE_USER")
     */
    public function toggle(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('app_task_list');
    }

    /**
     * @Route("/tasks/{id}/delete")
     */
    public function delete(Task $task, TaskService $taskService)
    {
        if($taskService->isRightUser($task, $this->getUser())) {
            $this->getDoctrine()->getManager()->remove($task);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('app_task_list');
        }

        throw new AccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action');
    }
}
