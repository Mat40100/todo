<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 07/12/2018
 * Time: 15:15
 */

namespace App\Tests\Service;

use App\Service\UserService;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;

class UserServiceTest extends TypeTestCase
{
    public function testEditUser()
    {
        $user = new User();

    }
}