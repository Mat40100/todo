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
     /**
     * @param User $user
     * @param Form $form
     */
    public function editUser(User $user, Form $form)
    {
        $role = $form->get('Admin')->getData();
        if($role === true) {
            $user->setRoles("ROLE_ADMIN");
        }
        else{
            $user->setRoles("ROLE_USER");
        }
    }
}