<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 08/12/2018
 * Time: 11:03.
 */

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Doctrine
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $password = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($password);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $password = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($password);
    }
}
