<?php
/**
 * Created by PhpStorm.
 * User: Mat
 * Date: 08/12/2018
 * Time: 13:49
 */

namespace App\Tests\EventListener;


use App\Entity\User;
use App\EventListener\Doctrine;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DoctrineTest extends TestCase
{
    public function testPreUpdate()
    {
        $user = new User();
            $user->setPassword('lol');

        $encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()->getMock();
        $encoder->method('encodePassword')->willReturn('ok');

        $lifecycleEventArgs = $this->getMockBuilder(LifecycleEventArgs::class)
            ->disableOriginalConstructor()->getMock();
        $lifecycleEventArgs->method('getObject')->willReturn($user);

        $listener = new Doctrine($encoder);

        $listener->preUpdate($lifecycleEventArgs);

        $this->assertSame('ok', $user->getPassword());
    }

    public function testPrePersist()
    {
        $user = new User();
        $user->setPassword('lol');

        $encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()->getMock();
        $encoder->method('encodePassword')->willReturn('ok');

        $lifecycleEventArgs = $this->getMockBuilder(LifecycleEventArgs::class)
            ->disableOriginalConstructor()->getMock();
        $lifecycleEventArgs->method('getObject')->willReturn($user);

        $listener = new Doctrine($encoder);

        $listener->preUpdate($lifecycleEventArgs);

        $this->assertSame('ok', $user->getPassword());
    }
}