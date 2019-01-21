<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 02/12/2018
 * Time: 19:27.
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Form\Form;

class UserService
{
    /**
     * @param User $user
     * @param Form $form
     */
    public function editUser(User $user, Form $form)
    {
        $role = $form->get('admin')->getData();
        $user->setRoles('ROLE_USER');

        if (true === $role) {
            $user->setRoles('ROLE_ADMIN');
        }
    }
}
