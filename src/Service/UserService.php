<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 02/12/2018
 * Time: 19:27
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $entitymanager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entitymanager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     *
     */
    public function userCreate(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $user->setRoles('ROLE_USER');

        $this->entitymanager->persist($user);
        $this->entitymanager->flush();
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function addRoleEntry(Form $form, User $user)
    {
        $data = false;
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            $data = true;
        }
        $form->add('Admin', CheckboxType::class, [
            'label' => 'Admin',
            'required' => false,
            'mapped'=>false,
            'data' => $data
        ]);

        return $form;
    }

    public function editUser(User $user, Form $form)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $role = $form->get('Admin')->getData();
        if($role === true) {
            $user->setRoles("ROLE_ADMIN");
        }
        else{
            $user->setRoles("ROLE_USER");
        }
        $this->entitymanager->flush();
    }
}