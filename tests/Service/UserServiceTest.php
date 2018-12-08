<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 08/12/2018
 * Time: 14:02
 */

namespace App\Tests\Service;


use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserServiceTest extends TypeTestCase
{
    private $validator;

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $this->validator = $this->getMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new ValidatorExtension($this->validator),
        );
    }

    public function testEditUser()
    {
        $service = new UserService();
        $user = new User();
        $user->setRoles('ROLE_ADMIN');
        $initialRoles = $user->getRoles();

        $formData = [
            "username" => "Emilie",
            "password" => [
                "first" => 'ecuelles',
                "second"=>'ecuelles'
            ],
            "email" => "1502@gmail.com"
        ];

        $form = $this->factory->create(UserType::class, $user, ['user' => $user]);
        $this->assertSame($form->get('Admin')->getData(), true); // Check form is well modified

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertSame($form->get('Admin')->getData(), false); //Check that form changed correctly
        $this->assertSame($user->getRoles(), $initialRoles ); // check that User object has not changed yet

        $service->editUser($user, $form);
        $this->assertSame($user->getRoles(), ['ROLE_USER'] ); //check User object changed correctly
    }
}